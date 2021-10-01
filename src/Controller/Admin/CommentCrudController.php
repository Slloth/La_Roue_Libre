<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextEditorField::new('content',"Contenu"),
            BooleanField::new('isChecked', "Commentaire vérifié ?"),
            AssociationField::new('user',"Utilisateur"),
            AssociationField::new('article',"Associé à cette article"),
            AssociationField::new('page',"Associé à cette page"),
            AssociationField::new('parent',"Réponse à"),
            DateTimeField::new('createdAt',"Date de création"),
            DateTimeField::new('updatedAt',"Date de dernière mise à jours"),
        ];
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->SetPageTitle('index',"Commentaires")
        ;
    }
    
    public function configureActions(Actions $actions): Actions
    {
        return $actions ->remove(Crud::PAGE_INDEX, Action::NEW)
                        ->remove(Crud::PAGE_INDEX, Action::EDIT)
                        ->add(Crud::PAGE_INDEX,Action::DETAIL)
                        ;
    }
}
