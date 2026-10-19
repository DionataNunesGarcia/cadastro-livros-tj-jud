<?php

namespace App\Controller;

use App\Entity\Livro;
use App\Exception\RegistroDuplicadoException;
use App\Exception\RegistroEmUsoException;
use App\Form\LivroType;
use App\Repository\LivroRepository;
use App\Service\CrudService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/livros')]
final class LivroController extends AbstractController
{
    #[Route(name: 'app_livro_index', methods: ['GET'])]
    public function index(LivroRepository $livroRepository): Response
    {
        return $this->render('livro/index.html.twig', [
            'livros' => $livroRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_livro_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CrudService $crud): Response
    {
        $livro = new Livro();
        $form = $this->createForm(LivroType::class, $livro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $crud->salvar($livro);
                $this->addFlash('success', 'Livro cadastrado com sucesso!');

                return $this->redirectToRoute('app_livro_index', [], Response::HTTP_SEE_OTHER);
            } catch (RegistroDuplicadoException $e) {
                $this->addFlash('danger', 'Já existe um livro com esses dados.');
            } catch (RegistroEmUsoException $e) {
                $this->addFlash('danger', 'Autor ou assunto inválido.');
            }
        }

        return $this->render('livro/new.html.twig', [
            'livro' => $livro,
            'form' => $form,
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/{codl}', name: 'app_livro_show', methods: ['GET'])]
    public function show(Livro $livro): Response
    {
        return $this->render('livro/show.html.twig', [
            'livro' => $livro,
        ]);
    }

    #[Route('/{codl}/edit', name: 'app_livro_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livro $livro, CrudService $crud): Response
    {
        $form = $this->createForm(LivroType::class, $livro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $crud->salvar($livro);
                $this->addFlash('success', 'Livro atualizado com sucesso!');

                return $this->redirectToRoute('app_livro_index', [], Response::HTTP_SEE_OTHER);
            } catch (RegistroDuplicadoException $e) {
                $this->addFlash('danger', 'Já existe um livro com esses dados.');
            } catch (RegistroEmUsoException $e) {
                $this->addFlash('danger', 'Autor ou assunto inválido.');
            }
        }

        return $this->render('livro/edit.html.twig', [
            'livro' => $livro,
            'form' => $form,
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/{codl}', name: 'app_livro_delete', methods: ['POST'])]
    public function delete(Request $request, Livro $livro, CrudService $crud): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livro->getCodl(), $request->getPayload()->getString('_token'))) {
            try {
                $crud->remover($livro);
                $this->addFlash('success', 'Livro removido com sucesso!');
            } catch (RegistroEmUsoException $e) {
                $this->addFlash('danger', 'Não é possível excluir este livro.');
            }
        }

        return $this->redirectToRoute('app_livro_index', [], Response::HTTP_SEE_OTHER);
    }
}
