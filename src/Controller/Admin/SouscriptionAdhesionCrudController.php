<?php

namespace App\Controller\Admin;

use App\Entity\SouscriptionAdhesion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SouscriptionAdhesionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SouscriptionAdhesion::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
