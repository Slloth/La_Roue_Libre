<?php

namespace App\Form;

use App\Entity\Content;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content',CKEditorType::class)
            ->add('position',ChoiceType::class,[
                'choices'  => [
                    'à droite' => "col-4",
                    'au milieu' => "offset-4 col-4",
                    'à gauche' => "offset-8 col-4",
                    'droite' => "col-6",
                    'gauche' => "offset-6 col-6",
                    'centré' => "col-12",
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Content::class,
        ]);
    }
}
