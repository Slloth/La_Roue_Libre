<?php

namespace App\Controller\Admin;

use App\Entity\Adherent;
use App\Entity\Adhesion;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Newsletter;
use App\Entity\Page;
use App\Entity\SouscriptionAdhesion;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use PhpParser\Node\Expr\Yield_;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('La Roue Libre');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section("Administration","fas fa-users-cog");
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-users', User::class)->setPermission("ROLE_ADMIN");
        yield MenuItem::section("Adhérents","fas fa-blog");
        yield MenuItem::linkToCrud('Adherent', 'fas fa-users', Adherent::class)->setPermission("ROLE_ACCUEIL");
        yield MenuItem::linkToCrud('Ajouter une adhésion', 'fa fa-check-to-slot', Adhesion::class)->setPermission("ROLE_ACCUEIL")->setAction('new');
        yield MenuItem::section("Contenu","fas fa-blog");
        yield MenuItem::linkToCrud('Newsletter', 'fas fa-users', Newsletter::class)->setPermission("ROLE_REDACTEUR");
        yield MenuItem::linkToCrud('Page', 'fas fa-columns', Page::class)->setPermission("ROLE_REDACTEUR");
        yield MenuItem::linkToCrud('Article', 'fas fa-newspaper', Article::class)->setPermission("ROLE_REDACTEUR");
        yield MenuItem::linkToCrud('Categorie', 'fas fa-tag', Category::class)->setPermission("ROLE_REDACTEUR");
        yield MenuItem::linkToCrud('Commentaire', 'fas fa-comments', Comment::class)->setPermission("ROLE_REDACTEUR");
        yield MenuItem::linkToRoute('Envrionnement', 'fas fa-cogs', 'admin_env')->setPermission("ROLE_REDACTEUR");
        yield MenuItem::section("Application","fas fa-window-maximize");
        yield MenuItem::linkToRoute("Envoie d'email", 'fas fa-envelope', 'admin_mail')->setPermission("ROLE_REDACTEUR");
        yield MenuItem::linkToRoute('MultiMédias', 'fas fa-images','admin_media')->setPermission("ROLE_REDACTEUR");
    }
}
