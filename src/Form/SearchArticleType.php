<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search',SearchType::class,[
                "label" => false,
                "required" => false,
                'attr' => [
                    'class' => "form-control mr-sm-2",
                    'placeholder' => "Effectuez une recherche"
                ]
            ])
            ->add("categories", EntityType::class,[
                "class" => Category::class,
                "label" => false,
                "required" => false,
                "expanded" => true,
                "multiple" => true
            ])
            ->add("Rechercher", SubmitType::class,[
                'attr' =>[
                    'class' => "btn btn-outline-success my-2 my-sm-0"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
