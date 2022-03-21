<?php

namespace App\Controller\Admin;

use App\Entity\Page;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Page::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('name','Nom'),
            DateTimeField::new('publicatedAt','Date de publication'),
            DateTimeField::new('updatedAt','Dernière mise à jours')->hideOnForm(),
            DateTimeField::new('createdAt','Date de création')->hideOnForm(),
            ChoiceField::new('status','Statut')->setChoices(['Publique' => 'Publique','Privé' => 'Privé','Corbeille' => 'Corbeille']),
            SlugField::new('slug')->setTargetFieldName('name'),
            TextEditorField::new('content','Contenu')->setFormType(CKEditorType::class)
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index',"Pages")
            ->setPageTitle('edit',"Page")
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
        ;
    }
}
