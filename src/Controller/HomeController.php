<?php

namespace App\Controller;

use App\Entity\Assunto;
use App\Entity\Autor;
use App\Entity\Livro;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('home/index.html.twig', [
            'totais' => [
                'livros' => $em->getRepository(Livro::class)->count([]),
                'autores' => $em->getRepository(Autor::class)->count([]),
                'assuntos' => $em->getRepository(Assunto::class)->count([]),
            ],
        ]);

    }
}
