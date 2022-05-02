<?php

namespace App\Controller\Admin;

use App\Entity\Adherent;
use App\Service\CsvExporter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FilterFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AdherentCrudController extends AbstractCrudController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator, private RequestStack $requestStack)
    {
    }

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
            ->setPageTitle('index',"Liste des adherents")
            ->setPageTitle('new',"Ajoutez un nouvel adherent")
            ->setPageTitle('edit',"Modifiez l'adherent")
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $export = Action::new('export', 'Exportez les adhérents')
        ->setIcon('fa fa-download')
        ->linkToUrl(function() {
            $request = $this->requestStack->getCurrentRequest();
                return $this->adminUrlGenerator->setAll($request->query->all())
                    ->setAction('export')
                    ->generateUrl();
        })
        ->setCssClass('btn')
        ->createAsGlobalAction();
        
        return $actions ->add(Crud::PAGE_INDEX, Action::DETAIL)
                        ->add(Crud::PAGE_INDEX, $export);
    }

    public function export(AdminContext $context, CsvExporter $csvExporter)
    {
        $fields = FieldCollection::new($this->configureFields(Crud::PAGE_INDEX));
        $filters = $this->get(FilterFactory::class)->create($context->getCrud()->getFiltersConfig(), $fields, $context->getEntity());
        $queryBuilder = $this   ->createIndexQueryBuilder($context->getSearch(), $context->getEntity(), $fields, $filters)
                                ->leftJoin('entity.adhesions',"adhe");
        return $csvExporter->createResponseFromQueryBuilder($queryBuilder, $fields, 'adherents.csv');
    }
}