<?php

namespace App\Controller;

use App\Entity\Assunto;
use App\Exception\RegistroDuplicadoException;
use App\Exception\RegistroEmUsoException;
use App\Form\AssuntoType;
use App\Repository\AssuntoRepository;
use App\Service\CrudService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/assuntos')]
final class AssuntoController extends AbstractController
{
    #[Route(name: 'app_assunto_index', methods: ['GET'])]
    public function index(Request $request, AssuntoRepository $assuntoRepository): Response
    {
        $q = trim((string) $request->query->get('q', ''));

        $qb = $assuntoRepository->createQueryBuilder('assunto')->orderBy('assunto.descricao', 'ASC');

        if ('' !== $q) {
            $qb->andWhere('assunto.descricao LIKE :q')->setParameter('q', '%' . $q . '%');
        }

        return $this->render('assunto/index.html.twig', [
            'assuntos' => $qb->getQuery()->getResult(),
            'filtro' => $q,
        ]);
    }

    #[Route('/new', name: 'app_assunto_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CrudService $crud): Response
    {
        $assunto = new Assunto();
        $form = $this->createForm(AssuntoType::class, $assunto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $crud->salvar($assunto);
                $this->addFlash('success', 'Assunto foi cadastrado com sucesso!');

                return $this->redirectToRoute('app_assunto_index', [], Response::HTTP_SEE_OTHER);
            } catch (RegistroDuplicadoException $e) {
                $this->addFlash('danger', 'Já existe um Assunto com esses dados.');
            }
        }

        return $this->render('assunto/new.html.twig', [
            'assunto' => $assunto,
            'form' => $form,
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/{codAs}', name: 'app_assunto_show', methods: ['GET'])]
    public function show(Assunto $assunto): Response
    {
        return $this->render('assunto/show.html.twig', [
            'assunto' => $assunto,
        ]);
    }

    #[Route('/{codAs}/edit', name: 'app_assunto_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Assunto $assunto, CrudService $crud): Response
    {
        $form = $this->createForm(AssuntoType::class, $assunto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $crud->salvar($assunto);
                $this->addFlash('success', 'Assunto atualizado com sucesso!');

                return $this->redirectToRoute('app_assunto_index', [], Response::HTTP_SEE_OTHER);
            } catch (RegistroDuplicadoException $e) {
                $this->addFlash('danger', 'Já existe um assunto com esses dados.');
            }
        }

        return $this->render('assunto/edit.html.twig', [
            'assunto' => $assunto,
            'form' => $form,
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }

    #[Route('/{codAs}', name: 'app_assunto_delete', methods: ['POST'])]
    public function delete(Request $request, Assunto $assunto, CrudService $crud): Response
    {
        if ($this->isCsrfTokenValid('delete'.$assunto->getCodAs(), $request->getPayload()->getString('_token'))) {
            try {
                $crud->remover($assunto);
                $this->addFlash('success', 'Assunto foi removido com sucesso!');
            } catch (RegistroEmUsoException $e) {
                $this->addFlash('danger', 'Não é possível excluir este assunto: existem livros vinculados.');
            }
        }

        return $this->redirectToRoute('app_assunto_index', [], Response::HTTP_SEE_OTHER);
    }
}
