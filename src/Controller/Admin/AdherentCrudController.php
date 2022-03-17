<?php

namespace App\Controller\Admin;

use App\Entity\Adherent;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AdherentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Adherent::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('fullName','nom complet'),
            TelephoneField::new('telephone', 'Téléphone'),
            EmailField::new('email', 'Email'),
            TextField::new('cp','Code Postal'),
            ArrayField::new('adhesions')->onlyOnDetail(),
            AssociationField::new('adhesions')->formatValue(function ($value, $entity) {
                $lastEntity = $entity->getAdhesions()->toArray();
                return end($lastEntity);
            })->onlyOnIndex(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->SetPageTitle('index',"Liste des adherents")
            ->SetPageTitle('new',"Ajoutez un nouvel adherent")
            ->SetPageTitle('edit',"Modifiez l'adherent")
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }
}
