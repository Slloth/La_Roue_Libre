<?php

namespace App\Controller\Admin;

use App\Entity\Adherent;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class AdherentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Adherent::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('nom','Nom'),
            TelephoneField::new('telephone', 'Téléphopne'),
            EmailField::new('email', 'Email'),
            AssociationField::new('souscriptionAdhesions', 'Dernière adhésion'),
            DateTimeField::new('CreatedAt', 'Date création adhésion')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->SetPageTitle('index',"Adherent")
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
