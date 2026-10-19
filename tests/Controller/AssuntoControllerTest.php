<?php

namespace App\Tests\Controller;

use App\Entity\Assunto;

final class AssuntoControllerTest extends AbstractCrudTestCase
{
    private const string PATH = '/assuntos';

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
                ->filter('form[name="assunto"]')
                ->form([
                    'assunto[descricao]' => 'Poesia',
                ])
        );

        self::assertResponseRedirects(self::PATH);
        self::assertNotNull(
            $this->em()
            ->getRepository(Assunto::class)
            ->findOneBy(['descricao' => 'Poesia'])
        );
    }

    public function testShow(): void
    {
        $fixture = new Assunto();
        $fixture->setDescricao('Poesia');

        $this->em()->persist($fixture);
        $this->em()->flush();

        $this->client->request('GET', sprintf('%s%s',self::PATH . '/', $fixture->getCodAs()));

        self::assertResponseStatusCodeSame(200);
        self::assertSelectorTextContains('body', 'Poesia');
        self::assertPageTitleContains('Assunto');
    }

    public function testEdit(): void
    {
        $fixture = (new Assunto())
            ->setDescricao('Estrutura de Dados');
        $this->em()->persist($fixture);
        $this->em()->flush();

        $crawler = $this->client->request('GET', self::PATH . '/' . $fixture->getCodAs() . '/edit');
        $this->client->submit($crawler->filter('form[name="assunto"]')->form([
            'assunto[descricao]' => 'Arquitetura de Dados',
        ]));

        self::assertResponseRedirects(self::PATH);
        self::assertSame('Arquitetura de Dados', $this->em()->find(Assunto::class, $fixture->getCodAs())?->getDescricao());
    }

    public function testRemove(): void
    {
        $fixture = (new Assunto())->setDescricao('Excluir Assunto');
        $this->em()->persist($fixture);
        $this->em()->flush();
        $id = $fixture->getCodAs();

        $crawler = $this->client->request('GET', self::PATH . '/' . $id);
        $this->client->submit($crawler->filter('form')->form());

        self::assertResponseRedirects(self::PATH);
        self::assertNull($this->em()->find(Assunto::class, $id));
    }
}
