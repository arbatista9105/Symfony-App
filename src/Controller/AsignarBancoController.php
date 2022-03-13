<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AsingarBancoType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AsignarBancoController extends AbstractController
{
    #[Route('/asignar/banco', name: 'app_asignar_banco_index')]
    public function index(UserRepository $userRepository,PaginatorInterface $paginator, Request $request): Response
    {
        $query = $userRepository->findAll();
        // Paginar los resultados de la consulta
        $paginator = $paginator->paginate(
        // Consulta Doctrine, no resultados
            $query,
            // Definir el parámetro de la página
            $request->query->getInt('page', 1),
            // Items per page
            2
        );
        return $this->render('asignar_banco/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    #[Route('/{id}/asignar/banco', name: 'app_banco_asignar', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {

        $form = $this->createForm(AsingarBancoType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository -> add($user);
            return $this->redirectToRoute('app_asignar_banco_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('asignar_banco/edit.html.twig', [
            'user' => $user,
            'form' => $form -> CreateView(),
        ]);
    }
}
