<?php

namespace App\Form;

use App\Entity\Color;
use App\Entity\Product;
use App\Entity\Size;
use App\Entity\SubCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom Produit'
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité'
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix Unitaire'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image'
            ])
            ->add('subcategory', EntityType::class, [
                'class' => SubCategory::class,
                'choice_label' => 'name',
                'label' => 'Sous Catégorie'
            ])
            ->add('color', EntityType::class, [
                'class' => Color::class,
                'choice_label' => 'name',
                'label' => 'Couleur'
            ])
            ->add('size', EntityType::class, [
                'class' => Size::class,
                'choice_label' => 'name',
                'label' => 'Taille'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
