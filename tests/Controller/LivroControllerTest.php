<?php

namespace App\Tests\Controller;

use App\Entity\Assunto;
use App\Entity\Autor;
use App\Entity\Livro;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Form;

final class LivroControllerTest extends AbstractCrudTestCase
{
    private const string PATH = '/livros';

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    private function criarLivro(string $titulo = 'Livro Base'): Livro
    {
        $livro = (new Livro())
            ->setTitulo($titulo)
            ->setEditora('Editora Base')
            ->setEdicao(1)
            ->setAnoPublicacao('2024')
            ->setValor('10.00');

        $this->em()->persist($livro);
        $this->em()->flush();

        return $livro;
    }

    public function testIndex(): void
    {
        $this->client->request('GET', self::PATH);
        self::assertResponseIsSuccessful();
    }

    public function testNew(): void
    {
        $autor = (new Autor())->setNome('Autor Teste');
        $assunto = (new Assunto())->setDescricao('Assunto Teste');
        $this->em()->persist($autor);
        $this->em()->persist($assunto);
        $this->em()->flush();

        $crawler = $this->client->request('GET', self::PATH.'/new');
        self::assertResponseIsSuccessful();

        $form = $crawler->filter('form[name="livro"]')->form([
            'livro[titulo]' => 'Livro Teste',
            'livro[editora]' => 'Editora Teste',
            'livro[edicao]' => '1',
            'livro[anoPublicacao]' => '2024',
            'livro[valor]' => '59,90',
        ]);

        $this->marcarCheckbox($crawler, $form, 'livro[autores]', (string) $autor->getCodAu());
        $this->marcarCheckbox($crawler, $form, 'livro[assuntos]', (string) $assunto->getCodAs());

        $this->client->submit($form);

        self::assertResponseRedirects(self::PATH);

        $livro = $this->em()->getRepository(Livro::class)->findOneBy(['titulo' => 'Livro Teste']);
        self::assertNotNull($livro);
        self::assertCount(1, $livro->getAutores());
        self::assertCount(1, $livro->getAssuntos());
    }

    private function marcarCheckbox(Crawler $crawler, Form $form, string $campo, string $valor): void
    {
        $valores = $crawler->filter(sprintf('input[name="%s[]"]', $campo))
            ->each(fn ($node) => $node->attr('value'));

        $posicao = array_search($valor, $valores, true);
        self::assertNotFalse($posicao, sprintf('Valor %s não encontrado em %s', $valor, $campo));

        $form[$campo.'['.$posicao.']']->tick();
    }

    public function testAnoInvalidoRetorna422(): void
    {
        $crawler = $this->client->request('GET', self::PATH.'/new');

        $this->client->submit($crawler->filter('form[name="livro"]')->form([
            'livro[titulo]' => 'Livro Teste',
            'livro[editora]' => 'Editora Teste',
            'livro[edicao]' => '1',
            'livro[anoPublicacao]' => '20X4',
            'livro[valor]' => '59,90',
        ]));

        self::assertResponseStatusCodeSame(422);
        self::assertSelectorTextContains('form', 'O ano deve conter exatamente 4 dígitos.');
    }

    public function testShow(): void
    {
        $fixture = $this->criarLivro('Livro Show');

        $this->client->request('GET', sprintf('%s%s', self::PATH . '/', $fixture->getCodl()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Livro');
        self::assertSelectorTextContains('body', 'Livro Show');
    }

    public function testEdit(): void
    {
        $fixture = $this->criarLivro();

        $crawler = $this->client->request('GET', self::PATH . '/' .$fixture->getCodl().'/edit');
        $this->client->submit($crawler->filter('form[name="livro"]')->form([
            'livro[titulo]' => 'Livro Editado',
            'livro[editora]' => 'Editora Base',
            'livro[edicao]' => '1',
            'livro[anoPublicacao]' => '2024',
            'livro[valor]' => '10,00',
        ]));

        self::assertResponseRedirects(self::PATH);
        self::assertSame('Livro Editado', $this->em()->find(Livro::class, $fixture->getCodl())?->getTitulo());
    }

    public function testRemove(): void
    {
        $fixture = $this->criarLivro('Livro Excluir');
        $id = $fixture->getCodl();

        $crawler = $this->client->request('GET', self::PATH . '/' . $id);
        $this->client->submit($crawler->filter('form')->form());

        self::assertResponseRedirects(self::PATH);
        self::assertNull($this->em()->find(Livro::class, $id));
    }
}
