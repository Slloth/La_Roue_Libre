<?php

namespace App\Controller\Admin;

use App\Entity\Adhesion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class AdhesionCrudController extends AbstractCrudController
{
    

    public static function getEntityFqcn(): string
    {
        return Adhesion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ChoiceField::new('prix','Adhesion')->setChoices(Adhesion::CHOICES),
            AssociationField::new('adherents')
        ];
    }
}
