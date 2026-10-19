<?php
namespace App\Controller;

use App\Service\RelatorioService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RelatorioController extends AbstractController
{
    #[Route('/relatorio/livros-por-autor', name: 'app_relatorio_livros_por_autor')]
    public function livrosPorAutor(Request $request, RelatorioService $relatorio): Response
    {
        $autor = $request->query->get('autor');

        return $this->render('relatorio/livros_por_autor.html.twig', [
            'porAutor' => $relatorio->livrosAgrupadosPorAutor($autor),
            'filtroAutor' => $autor,
        ]);
    }

    #[Route('/relatorio/livros-por-autor/pdf', name: 'app_relatorio_livros_por_autor_pdf')]
    public function livrosPorAutorPdf(Request $request, RelatorioService $relatorio): Response
    {
        $autor = $request->query->get('autor');

        $html = $this->renderView('relatorio/pdf/livros_por_autor.html.twig', [
            'porAutor' => $relatorio->livrosAgrupadosPorAutor($autor),
        ]);

        return new Response($relatorio->gerarPdf($html), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
