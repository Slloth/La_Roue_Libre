<?php

namespace App\Controller\Admin;

use App\Entity\Adherent;
use App\Repository\AdherentRepository;
use App\Repository\AdhesionRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class AdherentCrudController extends AbstractCrudController
{
    public function __construct(private AdhesionRepository $adhesionRepository)
    {
        
    }
    public static function getEntityFqcn(): string
    {
        return Adherent::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $adhesions = $this->adhesionRepository->findAll();
        $adhesions = array_combine($adhesions, $adhesions);
        //dd($adhesions);
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('nom','Nom'),
            TextField::new('Prenom','Prénom'),
            TelephoneField::new('telephone', 'Téléphone'),
            EmailField::new('email', 'Email'),
            DateTimeField::new('createdAt'),
            ArrayField::new('souscriptionAdhesions')
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
        return $actions ->add(Crud::PAGE_INDEX, Action::DETAIL)
                        ;
    }
}
