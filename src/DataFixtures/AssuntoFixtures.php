<?php

namespace App\DataFixtures;

use App\Entity\Assunto;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AssuntoFixtures extends Fixture
{
    private const array ASSUNTOS = [
        'assunto-romance' => 'Romance',
        'assunto-naturalismo' => 'Naturalismo',
        'assunto-realismo' => 'Realismo',
        'assunto-brasileira' => 'Literatura BR',
        'assunto-infantil' => 'Literatura infantil',
        'assunto-ficcao' => 'Ficção científica',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ASSUNTOS as $referencia => $nome) {
            $assunto = new Assunto();
            $assunto->setDescricao($nome);

            $manager->persist($assunto);
            $this->addReference($referencia, $assunto);
        }

        $manager->flush();
    }
}
