<?php

namespace App\DataFixtures;

use App\Entity\Autor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AutorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $autores = [
            'autor-machado' => 'Machado de Assis',
            'autor-clarice' => 'Clarice Lispector',
            'autor-lobato' => 'Monteiro Lobato',
            'autor-drummond' => 'Carlos Drummond de Andrade',
            'autor-jorge-amado' => 'Jorge Amado',
            'autor-conceicao' => 'Conceição Evaristo',
            'autor-paulo-coelho' => 'Paulo Coelho',
            'autor-verissimo' => 'Luis Fernando Verissimo',
            'autor-bandeira' => 'Manuel Bandeira',
            'autor-cecilia' => 'Cecília Meireles',
            'autor-azevedo' => 'Aluísio Azevedo',
            'autor-rosa' => 'Guimarães Rosa',
        ];

        foreach ($autores as $referencia => $nome) {
            $autor = new Autor();
            $autor->setNome($nome);

            $manager->persist($autor);
            $this->addReference($referencia, $autor);
        }

        $manager->flush();
    }
}
