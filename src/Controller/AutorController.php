<?php

namespace App\Controller;

use App\Entity\Autor;
use App\Exception\RegistroDuplicadoException;
use App\Exception\RegistroEmUsoException;
use App\Form\AutorType;
use App\Repository\AutorRepository;
use App\Service\CrudService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/autores')]
final class AutorController extends AbstractController
{
    #[Route(name: 'app_autor_index', methods: ['GET'])]
    public function index(Request $request, AutorRepository $autorRepository): Response
    {
        $q = trim((string) $request->query->get('q', ''));

        $qb = $autorRepository->createQueryBuilder('au')->orderBy('au.nome', 'ASC');

        if ('' !== $q) {
            $qb->andWhere('au.nome LIKE :q')->setParameter('q', '%' . $q . '%');
        }

        return $this->render('autor/index.html.twig', [
            'autores' => $qb->getQuery()->getResult(),
            'filtro' => $q,
        ]);
    }

    #[Route('/new', name: 'app_autor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CrudService $crud): Response
    {
        $autor = new Autor();
        $form = $this->createForm(AutorType::class, $autor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $crud->salvar($autor);
                $this->addFlash('success', 'Autor cadastrado com sucesso!');

                return $this->redirectToRoute('app_autor_index', [], Response::HTTP_SEE_OTHER);
            } catch (RegistroDuplicadoException $e) {
                $this->addFlash('danger', 'Já existe um autor com esses dados.');
            }
        }

        return $this->render('autor/new.html.twig', [
            'autor' => $autor,
            'form' => $form,
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/{codAu}', name: 'app_autor_show', methods: ['GET'])]
    public function show(Autor $autor): Response
    {
        return $this->render('autor/show.html.twig', [
            'autor' => $autor,
        ]);
    }

    #[Route('/{codAu}/edit', name: 'app_autor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Autor $autor, CrudService $crud): Response
    {
        $form = $this->createForm(AutorType::class, $autor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $crud->salvar($autor);
                $this->addFlash('success', 'Autor atualizado com sucesso!');

                return $this->redirectToRoute('app_autor_index', [], Response::HTTP_SEE_OTHER);
            } catch (RegistroDuplicadoException $e) {
                $this->addFlash('danger', 'Já existe um autor com esses dados.');
            }
        }

        return $this->render('autor/edit.html.twig', [
            'autor' => $autor,
            'form' => $form,
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/{codAu}', name: 'app_autor_delete', methods: ['POST'])]
    public function delete(Request $request, Autor $autor, CrudService $crud): Response
    {
        if ($this->isCsrfTokenValid('delete'.$autor->getCodAu(), $request->getPayload()->getString('_token'))) {
            try {
                $crud->remover($autor);
                $this->addFlash('success', 'Autor removido com sucesso!');
            } catch (RegistroEmUsoException $e) {
                $this->addFlash('danger', 'Não é possível excluir este autor: existem livros vinculados.');
            }
        }

        return $this->redirectToRoute('app_autor_index', [], Response::HTTP_SEE_OTHER);
    }
}
