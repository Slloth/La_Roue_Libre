<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Service\CsvExporter;
use Symfony\Component\HttpFoundation\Request;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FilterFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator, private RequestStack $requestStack)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('name','Nom'),
            DateTimeField::new('publicatedAt','Date de publication'),
            DateTimeField::new('updatedAt','Dernière mise à jours')->hideOnForm(),
            DateTimeField::new('createdAt','Date de création')->hideOnForm(),
            ChoiceField::new('status','Statut')->setChoices(['Publique' => 'Publique','Privé' => 'Privé','Corbeille' => 'Corbeille']),
            SlugField::new('slug')->setTargetFieldName('name'),
            AssociationField::new('user',"Utilisateur")->hideOnForm(),
            AssociationField::new('category',"Catégorie(s)")->formatValue(function ($value, $entity) {
                return implode(",",$entity->getCategory()->toArray());
            }),
            TextField::new('thumbnailFile','Miniature')->setFormType(VichImageType::class)->onlyOnForms(),
            ImageField::new('thumbnail',"Miniature")->setBasePath("/uploads/images")->onlyOnIndex(),
            TextEditorField::new('content','Contenu')->setFormType(CKEditorType::class)
        ];
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index',"Articles")
            ->setPageTitle('edit',"Article")
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $export = Action::new('export', 'actions.export')
        ->setIcon('fa fa-download')
        ->linkToUrl(function() {
            $request = $this->requestStack->getCurrentRequest();
                return $this->adminUrlGenerator->setAll($request->query->all())
                    ->setAction('export')
                    ->generateUrl();
        })
        ->setCssClass('btn')
        ->createAsGlobalAction();

        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL)
                        ->add(Crud::PAGE_INDEX, $export);
    }

    public function export(AdminContext $context, CsvExporter $csvExporter)
    {
        $fields = FieldCollection::new($this->configureFields(Crud::PAGE_INDEX));
        $filters = $this->get(FilterFactory::class)->create($context->getCrud()->getFiltersConfig(), $fields, $context->getEntity());
        $queryBuilder = $this->createIndexQueryBuilder($context->getSearch(), $context->getEntity(), $fields, $filters);

        return $csvExporter->createResponseFromQueryBuilder($queryBuilder, $fields, 'articles.csv');
    }
}
