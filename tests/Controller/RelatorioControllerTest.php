<?php

namespace App\Tests\Controller;

use App\Entity\Autor;
use App\Entity\Livro;

final class RelatorioControllerTest extends AbstractCrudTestCase
{
    public function testTelaDoRelatorio(): void
    {
        $this->client->request('GET', '/relatorio/livros-por-autor');
        self::assertResponseIsSuccessful();
    }

    public function testPdfDoRelatorio(): void
    {
        $this->client->request('GET', '/relatorio/livros-por-autor/pdf');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('Content-Type', 'application/pdf');
        self::assertStringStartsWith('%PDF', $this->client->getResponse()->getContent());
    }

    public function testFiltroPorAutor(): void
    {
        $autor = (new Autor())->setNome('Autor Filtrado');
        $livro = (new Livro())->setTitulo('Livro Filtrado')->setEditora('Editora Base')
            ->setEdicao(1)->setAnoPublicacao('2024')->setValor('10.00');
        $livro->addAutor($autor);
        $this->em()->persist($autor);
        $this->em()->persist($livro);
        $this->em()->flush();

        $this->client->request('GET', '/relatorio/livros-por-autor?autor=Filtrado');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('body', 'Livro Filtrado');
    }
}
