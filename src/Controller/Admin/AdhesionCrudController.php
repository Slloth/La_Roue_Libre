<?php

namespace App\Controller\Admin;

use App\Entity\Adhesion;
use App\Repository\TypeAdhesionRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Component\HttpFoundation\RedirectResponse;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Exception;

class AdhesionCrudController extends AbstractCrudController
{
    public function __construct(private TypeAdhesionRepository $typeAdhesionRepository, private AdminUrlGenerator $adminUrlGenerator) {
    }

    public static function getEntityFqcn(): string
    {
        return Adhesion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        //Récupère uniquement les prix des types adhésions
        $choices = $this->typeAdhesionRepository->findAll();
        if(!$choices){
            throw new Exception("Attention ! Aucun Prix n'as été trouvé en base de données, rendez vous dans l'onglet \"Gestion des types d'adhésions\" Pour les créers ");
        }
        foreach($choices as $choice){
            array_push($choices,$choice);
        }
        // Fait en sorte que les clées et les valeurs du tableau soit identique
        $choices = array_combine($choices,$choices);
        $branch = ["Le Havre" => "Le Havre", "Montivilliers" => "Montivilliers", "Harfleur" => "Harfleur"];
        return [
            ChoiceField::new('prix','Adhesion')->setChoices($choices),
            ChoiceField::new('branch','Antenne')->setChoices($branch),
            DateTimeField::new('subscribedAt',"Date d'adhésions"),
            AssociationField::new('adherents')
        ];
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('new',"Ajout d'une nouvelle adhesion")
        ;
    }

    protected function getRedirectResponseAfterSave(AdminContext $context, string $action): RedirectResponse
    {
        $submitButtonName = $context->getRequest()->request->all()['ea']['newForm']['btn'];
        if (Action::SAVE_AND_RETURN === $submitButtonName) {
            $url = $context->getReferrer()
            ?? $this->container->get(AdminUrlGenerator::class)->setController(AdherentCrudController::class)->setAction(Action::INDEX)->generateUrl();
            
            return $this->redirect($url);
        }
        
        return parent::getRedirectResponseAfterSave($context,$action);
    }
}
