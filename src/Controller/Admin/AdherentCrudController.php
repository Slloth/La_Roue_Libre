<?php

namespace App\Controller\Admin;

use App\Entity\Adherent;
use App\Repository\AdherentRepository;
use App\Repository\AdhesionRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use Symfony\Component\Validator\Constraints\Choice;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
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
       
        //dd($adhesions);
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('nom','Nom'),
            TextField::new('Prenom','Prénom'),
            TelephoneField::new('telephone', 'Téléphone'),
            EmailField::new('email', 'Email'),
            //DateTimeField::new('createdAt'),
            AssociationField::new('souscriptionAdhesions')->formatValue(function ($value, $entity) {
                return implode(",",$entity->getSouscriptionAdhesions()->toArray());
            })->hideOnForm(),
            AssociationField::new('souscriptionAdhesions')->autocomplete()->hideOnIndex()->formatValue(static function($value){
                
            })
            //->setFormTypeOptions(['by_reference' => false])
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
