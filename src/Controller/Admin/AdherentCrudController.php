<?php

namespace App\Controller\Admin;

use App\Entity\Adherent;
use App\Repository\SouscriptionAdhesionRepository;
use App\Repository\AdherentRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
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
            TelephoneField::new('telephone', 'Téléphone'),
            EmailField::new('email', 'Email'),
            ArrayField::new(strval(AssociationField::new('souscriptionAdhesions', 'Adhésions')->setQueryBuilder(function ($queryBuilder) {
                return $queryBuilder
                                    ->join('entity.adhesions','a')
                                    ->andWhere('entity.id = 3');
            })) ),
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

    public function relationAdhe(AdherentRepository $adherentRepository): array
    {
        $sql = '
            SELECT type FROM adhesion
            JOIN souscription_adhesion ON souscription_adhesion.id = adhesion.id
            JOIN adherent ON souscription_adhesion.id = adherent.id;
            ';
        $stmt = $adherentRepository->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }
}
