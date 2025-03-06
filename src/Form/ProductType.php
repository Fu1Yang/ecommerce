<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Products;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                "attr"=>["placeholder" => "Nom du produit"]
            ])
            ->add('description', TextareaType::class,[
                "attr"=>["placeholder" => "Description du produit"]
            ])
            ->add('price', NumberType::class,[
                "attr"=>["placeholder" => "Prix du produit"]
            ])
            ->add('stock', NumberType::class,[
                "attr"=>["placeholder" => "Stock du produit"]
            ])
            ->add('isValid', CheckboxType::class)
            ->add('category', EntityType::class, [
                'class' => categories::class,
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
