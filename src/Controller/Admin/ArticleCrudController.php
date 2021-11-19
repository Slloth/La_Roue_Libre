<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
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
            AssociationField::new('user',"Utilisateur")->hideOnForm(),
            AssociationField::new('category',"Catégorie(s)"),
            TextField::new('thumbnailFile','Miniature')->setFormType(VichImageType::class)->onlyOnForms(),
            ImageField::new('thumbnail',"Miniature")->setBasePath("/uploads/images")->onlyOnIndex(),
            TextEditorField::new('content','Contenu')->setFormType(CKEditorType::class)
        ];
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->SetPageTitle('index',"Articles")
            ->SetPageTitle('edit',"Article")
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
