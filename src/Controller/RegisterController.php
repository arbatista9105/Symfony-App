<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, ManagerRegistry $doctrine,UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form -> handleRequest($request);
        if ($form -> isSubmitted() && $form -> isValid()){
            if ( $user->getPassword() === $user->getPasswordConfirm()) {
                if($user->getCountry() != null){
                    $em = $doctrine->getManager();
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
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash('success', User::SUCCESS);
                    return $this->redirectToRoute('login');
                }
            }
                $this->addFlash('error','La contraseña no coincide');
                $this->addFlash('error_country','Debe seleccionar un país');

        }

        return $this->render('register/index.html.twig', [
            'RegisterUserForm' => $form -> CreateView()
        ]);
    }
}
