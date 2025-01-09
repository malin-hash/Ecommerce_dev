<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'Votre E-mail',
                'constraints' =>  [
                    new NotBlank()
                ]

            ])
            ->add('name', TextType::class, [
                'label' => 'Votre Nom',
                'constraints' =>  [
                    new NotBlank()
                ]

            ])
            ->add('firstname', TextType::class, [
                'label' => 'Votre Prénom',
                'constraints' =>  [
                    new NotBlank()
                ]

            ])
            ->add('streetnumber', IntegerType::class, [
                'label' => 'Votre Rue',
                'constraints' =>  [
                    new NotBlank()
                ]

            ])
            ->add('address', TextType::class, [
                'label' => 'Votre Adresse',
                'constraints' =>  [
                    new NotBlank()
                ]

            ])
            ->add('phonenumber', IntegerType::class, [
                'label' => 'Votre Téléphone',
                'constraints' =>  [
                    new NotBlank()
                ]

            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'label' => 'Votre Pays'
            ])
            ->add('City', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'label' => 'Votre Ville'
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => 'Votre Mot de passe',
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new PasswordStrength(
                        minScore: PasswordStrength::STRENGTH_STRONG,
                        message: "Votre mot de passe n'est pas assez fort"
                    )
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
