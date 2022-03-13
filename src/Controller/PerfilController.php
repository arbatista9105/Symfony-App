<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswType;
use App\Form\PerfilType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class PerfilController extends AbstractController
{
    #[Route('/', name: 'app_perfil')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        if($user) {
            //$user_rol = ($user->getRoles()[0]);
            if($user->getRoles()[0] === 'ROLE_ADMIN') {
                return $this->render('perfil/admin.html.twig', [
                    'user' => $user
                ]);
            }
            if ($user->getRoles()[0] === 'ROLE_USER'){
                return $this->render('perfil/index.html.twig', [
                    'user' => $user
                ]);
            }
            if ($user->getRoles()[0] === 'ROLE_GD'){
                return $this->render('perfil/gd.html.twig', [
                    'user' => $user
                ]);
            }
        }else{
            return $this->redirectToRoute('login');
        }
    }

    /**
     * @Route("/{id}/edit", name="perfil_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger, User $user)
    {
        $form = $this->createForm(PerfilType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($user->getCountry() != null){
                $logger->info('Formulario válido');

                $user = $form->getData();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'El perfil ha sido actualizado correctamente');

                return $this->redirectToRoute('app_perfil');
            }
            $this->addFlash('error_country','Debe seleccionar un país');
        }

        $logger->info('Formulario inválido');
        dump($form);

        return $this->render('perfil/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit_passw", name="perfil_edit_passw", methods={"GET","POST"})
     */
    public function editPassw(Request $request, EntityManagerInterface $entityManager, LoggerInterface $logger, User $user,UserPasswordHasherInterface $passwordHasher,AuthenticationUtils $authenticationUtils)
    {
        $form = $this->createForm(PasswType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $logger->info('Formulario válido');

            $user = $form->getData();
            if ( $user->getPassword() === $user->getPasswordConfirm())
            {
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

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'La contraseña se actualizo correctamente');

                return $this->redirectToRoute('app_perfil');
            }else{
                $this->addFlash('error','La contraseña no coincide');
            }

        }

        $logger->info('Formulario inválido');
        dump($form);

        return $this->render('perfil/update_passw.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
