<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\User1Type;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
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
        return $this->render('user/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ( $user->getPassword() === $user->getPasswordConfirm()) {
                if ($user->getCountry() != null) {
                    $user->setRoles(['ROLE_USER']);
                    $hashedPassword = $passwordHasher->hashPassword(
                        $user,
                        $user->getPassword()
                    );
                    $user->setPassword($hashedPassword);
                    $hashedPassword = $passwordHasher->hashPassword(
                        $user,
                        $user->getPasswordConfirm()
                    );
                    $user->setPasswordConfirm($hashedPassword);
                    $userRepository->add($user);
                    return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
                }
            }
                  $this->addFlash('error','La contraseña no coincide');
                  $this->addFlash('error_country','Debe seleccionar un país');
        }


        return $this->render('user/new.html.twig', [
            'user' => $user,
            'formUser' => $form -> CreateView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($user->getCountry() != null){
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                );
                $user->setPassword($hashedPassword);
                $userRepository->add($user);
                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            }
             $this->addFlash('error_country','Debe seleccionar un país');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'formUser' => $form -> CreateView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
