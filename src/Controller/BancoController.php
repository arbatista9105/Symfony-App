<?php

namespace App\Controller;

use App\Entity\Banco;
use App\Form\BancoType;
use App\Repository\BancoRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/banco')]
class BancoController extends AbstractController
{
    #[Route('/', name: 'app_banco_index', methods: ['GET'])]
    public function index(BancoRepository $bancoRepository,PaginatorInterface $paginator, Request $request): Response
    {
        $query = $bancoRepository->findAll();
        // Paginar los resultados de la consulta
        $paginator = $paginator->paginate(
        // Consulta Doctrine, no resultados
            $query,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('banco/index.html.twig', [
            'paginator' => $paginator
        ]);
    }

    #[Route('/new', name: 'app_banco_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BancoRepository $bancoRepository): Response
    {
        $banco = new Banco();
        $form = $this->createForm(BancoType::class, $banco);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bancoRepository->add($banco);
            return $this->redirectToRoute('app_banco_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('banco/new.html.twig', [
            'banco' => $banco,
            'form' => $form -> CreateView(),
        ]);
    }

    #[Route('/{id}', name: 'app_banco_show', methods: ['GET'])]
    public function show(Banco $banco): Response
    {
        return $this->render('banco/show.html.twig', [
            'banco' => $banco,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_banco_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Banco $banco, BancoRepository $bancoRepository): Response
    {
        $form = $this->createForm(BancoType::class, $banco);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bancoRepository->add($banco);
            return $this->redirectToRoute('app_banco_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('banco/edit.html.twig', [
            'banco' => $banco,
            'form' => $form -> CreateView(),
        ]);
    }

    #[Route('/{id}', name: 'app_banco_delete', methods: ['POST'])]
    public function delete(Request $request, Banco $banco, BancoRepository $bancoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$banco->getId(), $request->request->get('_token'))) {
            $bancoRepository->remove($banco);
        }

        return $this->redirectToRoute('app_banco_index', [], Response::HTTP_SEE_OTHER);
    }
}
