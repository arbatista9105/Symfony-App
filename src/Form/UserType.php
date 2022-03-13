<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,  [
                 'constraints' => [
                     new NotBlank()
                   ],
                 'label' => 'Nombre'
                 ])
            ->add('lastname',TextType::class,  [
                'constraints' => [
                    new NotBlank()
                ],
                'label' => 'Apellidos'
            ])
            ->add('dni',NumberType::class,  [
                'constraints' => [
                    new NotBlank(),
                    new Length(11)
                ],
                'label' => 'Cédula'
            ])
            ->add('country',ChoiceType::class,  [
                'choices' => [
                    'Seleccione el país' =>  null,
                    'Cuba'               => 'Cuba',
                    'Canada'             => 'Canada'
                ],
                'label' => 'País'
            ])
        ->add('email', EmailType::class, [
                    'constraints' => [
                        new NotBlank()
                    ],
                    'label' => 'Correo'
                ]
            )
            ->add('password', PasswordType::class,
                [ 'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 4])
                ],
                    'label' => 'Contraseña',
                ])
            ->add('password_confirm',PasswordType::class,  [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 4])
                ],
                'label' => 'Confirmar Contraseña'
            ])
            ->add('Registrar', SubmitType::class);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
