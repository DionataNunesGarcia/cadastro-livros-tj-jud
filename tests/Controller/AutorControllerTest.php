<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\Entity\Autor;
use App\Entity\Livro;

final class AutorControllerTest extends AbstractCrudTestCase
{
    private const string PATH = '/autores';

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testIndex(): void
    {
        $this->client->request('GET', self::PATH);
        self::assertResponseIsSuccessful();
    }

    public function testNew(): void
    {
        $crawler = $this->client->request('GET', self::PATH . '/new');
        self::assertResponseIsSuccessful();

        $this->client->submit(
            $crawler
                ->filter('form[name="autor"]')
                ->form([
                    'autor[nome]' => 'Autor teste',
                ])
        );

        self::assertResponseRedirects(self::PATH);
        self::assertNotNull(
            $this->em()
                ->getRepository(Autor::class)
                ->findOneBy(['nome' => 'Autor teste'])
        );
    }

    public function testShow(): void
    {
        $fixture = new Autor();
        $fixture->setNome('Autor Show');

        $this->em()->persist($fixture);
        $this->em()->flush();

        $this->client->request('GET', sprintf('%s%s', self::PATH . '/', $fixture->getCodAu()));

        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('body', 'Autor Show');
        self::assertPageTitleContains('Autor');
    }

    public function testEdit(): void
    {
        $fixture = new Autor();
        $fixture->setNome('Fulano Silva');

        $this->em()->persist($fixture);
        $this->em()->flush();

        $crawler = $this->client->request('GET', self::PATH . '/' . $fixture->getCodAu() . '/edit');
        $this->client->submit($crawler->filter('form[name="autor"]')->form([
            'autor[nome]' => 'Beltrano Silva',
        ]));

        self::assertResponseRedirects(self::PATH);
        self::assertSame('Beltrano Silva', $this->em()->find(Autor::class, $fixture->getCodAu())?->getNome());
    }

    public function testRemove(): void
    {
        $fixture = (new Autor())->setNome('Excluir Autor');
        $this->em()->persist($fixture);
        $this->em()->flush();
        $id = $fixture->getCodAu();

        $crawler = $this->client->request('GET', self::PATH . '/' .$id);
        $this->client->submit($crawler->filter('form')->form());

        self::assertResponseRedirects(self::PATH);
        self::assertNull($this->em()->find(Autor::class, $id));
    }

    public function testNaoPermiteExcluirAutorComLivros(): void
    {
        $autor = (new Autor())
            ->setNome('Autor Vinculado');

        $livro = (new Livro())
            ->setTitulo('Livro do Autor')
            ->setEditora('Editora Base')
            ->setEdicao(1)
            ->setAnoPublicacao('2024')
            ->setValor('10.00');

        $livro->addAutor($autor);

        $this->em()->persist($autor);
        $this->em()->persist($livro);

        $this->em()->flush();
        $id = $autor->getCodAu();

        $crawler = $this->client->request('GET', self::PATH . '/' . $id);
        $this->client->submit($crawler->filter('form')->form());

        self::assertResponseRedirects(self::PATH);
        $this->client->followRedirect();
        self::assertSelectorTextContains('body', 'Não é possível excluir este autor');
        self::assertNotNull($this->em()->find(Autor::class, $id));
    }
}
