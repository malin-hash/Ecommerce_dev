<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\Orders;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => false
            ])
            ->add('firstname', null, [
                'label' => false
            ])
            ->add('email', null, [
                'label' => false
            ])
            ->add('phonenumber', IntegerType::class, [
                'label' => false
            ])
            ->add('address', null, [
                'label' => false
            ])
            ->add('postalcode', IntegerType::class, [
                'label' => false
            ])
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'choice_label' => 'name',
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('City', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
        ]);
    }
}
