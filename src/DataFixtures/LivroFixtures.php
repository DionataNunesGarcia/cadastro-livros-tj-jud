<?php

namespace App\DataFixtures;

use App\Entity\Assunto;
use App\Entity\Autor;
use App\Entity\Livro;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LivroFixtures extends Fixture implements DependentFixtureInterface
{
    private const array LIVROS = [
        [
            'titulo' => 'Dom Casmurro',
            'editora' => 'Garnier',
            'edicao' => 1,
            'anoPublicacao' => '1899',
            'valor' => '49.90',
            'autores' => ['autor-machado'],
            'assuntos' => ['assunto-romance'],
        ],
        [
            'titulo' => 'O Cortiço',
            'editora' => 'Garnier',
            'edicao' => 2,
            'anoPublicacao' => '1890',
            'valor' => '39.90',
            'autores' => ['autor-azevedo'],
            'assuntos' => ['assunto-romance', 'assunto-naturalismo'],
        ],
        [
            'titulo' => 'Memórias Póstumas',
            'editora' => 'Garnier',
            'edicao' => 3,
            'anoPublicacao' => '1881',
            'valor' => '59.90',
            'autores' => ['autor-machado'],
            'assuntos' => ['assunto-romance', 'assunto-realismo'],
        ],
        [
            'titulo' => 'Grande Sertão: Veredas',
            'editora' => 'José Olympio',
            'edicao' => 1,
            'anoPublicacao' => '1956',
            'valor' => '89.90',
            'autores' => ['autor-rosa'],
            'assuntos' => ['assunto-romance'],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::LIVROS as $item) {
            $livro = new Livro();
            $livro->setTitulo($item['titulo']);
            $livro->setEditora($item['editora']);
            $livro->setEdicao($item['edicao']);
            $livro->setAnoPublicacao($item['anoPublicacao']);
            $livro->setValor($item['valor']);

            foreach ($item['autores'] as $ref) {
                $livro->addAutor($this->getReference($ref, Autor::class));
            }

            foreach ($item['assuntos'] as $ref) {
                $livro->addAssunto($this->getReference($ref, Assunto::class));
            }

            $manager->persist($livro);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AutorFixtures::class,
            AssuntoFixtures::class,
        ];
    }
}
