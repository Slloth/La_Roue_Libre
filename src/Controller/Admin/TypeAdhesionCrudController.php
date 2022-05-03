<?php

namespace App\Controller\Admin;

use App\Entity\TypeAdhesion;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TypeAdhesionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TypeAdhesion::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('typeAdhesion'),
            MoneyField::new('prix')->setCurrency('EUR')->setCustomOption(MoneyField::OPTION_STORED_AS_CENTS,false),
            DateTimeField::new('createdAt')->hideOnForm()
        ];
    }
}
