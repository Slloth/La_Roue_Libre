<?php

namespace App\Form;

use App\Entity\Email;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('emailFrom', EmailType::class,[
                "label" => false,
                "attr" =>[
                    "placeholder" => "Votre email"
                ]
            ])
            ->add('subject', TextType::class,[
                "label" => false,   
                "attr" =>[
                    "placeholder" => "Le sujet de votre mail"
                ]
            ])
            ->add('content',CKEditorType::class,[
                "label" => false, 
                "config_name" => "public_config",
            ])
            ->add('Envoyer',SubmitType::class,[
                'row_attr' => [
                    "class" =>"d-grid gap-2 mt-5"
                ],
                'attr' =>[
                    'class' => "btn btn-success py-3"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Email::class,
        ]);
    }
}
