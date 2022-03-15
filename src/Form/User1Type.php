<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Config\Definition\Exception\Exception;
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


class User1Type extends AbstractType
{
    private function getPaisApi(): array {

        //api res pais

        try {
            $json = json_decode( file_get_contents('https://restcountries.com/v2/all?fields=name,alpha2Code'),true);
            $cadena = array();
            foreach ($json as $value) {
                array_push($cadena,$value['name'] . ', ' . $value['alpha2Code']);
            }
            return $cadena;
        } catch (\TypeError $e) {
//              print_r("Error: " . $e->getMessage() . PHP_EOL) ;
            throw new Exception("Error de conexión con la api de país. Revise la conexión a internet y recarge la página. Si el error persiste contacte con el admin del sistema. ") ;

        } catch (\ErrorException  $e) {
//              print_r("Error: " . $e->getMessage() . PHP_EOL) ;
              throw new Exception("Error de conexión con la api res de país. Revise la conexión a internet y recarge la página. Si el error persiste contacte con el admin del sistema. ") ;

        }

    }

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
            ->add('country',ChoiceType::class, [
                'choices' => $this->getPaisApi(),
                'choice_label' => function ($value) {
                    return $value;
                },
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
            ->add('Guardar', SubmitType::class);

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
