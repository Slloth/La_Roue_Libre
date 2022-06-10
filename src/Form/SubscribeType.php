<?php

namespace App\Form;

use App\Entity\Newsletter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscribeType extends AbstractType
{ 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                "label" => false,
                'attr' => [
                    "placeholder" => "Adresse Mail"
                ]
            ])
            ->add('agreeTerms', CheckboxType::class,[
                "mapped" => false,
                "label" => "Acceptez les terms",
                "label_attr" => [
                    "style" => "color: white;"
                ]
            ])
            ->add('Souscrire', SubmitType::class,[
                'attr' => [
                    "class" => "btn btn-outline-orange"
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Newsletter::class,
            //'csrf_protection' => false
        ]);
    }
}
