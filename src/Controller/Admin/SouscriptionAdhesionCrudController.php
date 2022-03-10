<?php

namespace App\Controller\Admin;

use App\Entity\SouscriptionAdhesion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class SouscriptionAdhesionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SouscriptionAdhesion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('adherents')->onlyOnForms(),
            AssociationField::new('adhesions')
        ];
    }
}
