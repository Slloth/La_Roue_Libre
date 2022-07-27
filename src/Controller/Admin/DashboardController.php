<?php

namespace App\Controller\Admin;

use App\Entity\Page;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Adherent;
use App\Entity\Adhesion;
use App\Entity\Category;
use App\Entity\Newsletter;
use App\Entity\TypeAdhesion;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

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
        yield MenuItem::linkToCrud('Administration des utilisateurs', 'fas fa-users-cog', User::class)->setPermission("ROLE_ADMIN");
        yield MenuItem::subMenu("Adhérents","fas fa-users")->setSubItems([
            MenuItem::linkToCrud('liste des adherents', 'fas fa-user', Adherent::class)->setPermission("ROLE_ACCUEIL"),
            MenuItem::linkToCrud('Ajouter une adhésion', 'fas fa-edit', Adhesion::class)->setPermission("ROLE_ACCUEIL")->setAction('new'),
            MenuItem::linkToCrud('Gestion des types d\'adhésions', 'fas fa-money-check-alt', TypeAdhesion::class)->setPermission("ROLE_ACCUEIL")
        ]);
        yield MenuItem::section("Contenu","fas fa-blog");
        yield MenuItem::linkToCrud('Inscrit à la newsletter', 'fas fa-user-check', Newsletter::class)->setPermission("ROLE_REDACTEUR");
        yield MenuItem::linkToCrud('Page', 'fas fa-columns', Page::class)->setPermission("ROLE_REDACTEUR");
        yield MenuItem::linkToCrud('Article', 'fas fa-newspaper', Article::class)->setPermission("ROLE_REDACTEUR");
        yield MenuItem::linkToCrud('Categorie', 'fas fa-tag', Category::class)->setPermission("ROLE_REDACTEUR");
        yield MenuItem::linkToCrud('Commentaire', 'fas fa-comments', Comment::class)->setPermission("ROLE_REDACTEUR");
        yield MenuItem::linkToRoute('Envrionnement', 'fas fa-cogs', 'admin_env')->setPermission("ROLE_REDACTEUR");
        yield MenuItem::section("Application","fas fa-window-maximize");
        yield MenuItem::linkToRoute("Envoie d'email aux abonnés", 'fas fa-envelope-open', 'admin_mail_newsletter')->setPermission("ROLE_REDACTEUR");
        yield MenuItem::linkToRoute("Envoie d'email aux adhérents", 'fas fa-envelope-open-text', 'admin_mail_adherent')->setPermission("ROLE_REDACTEUR");
        yield MenuItem::linkToRoute('MultiMédias', 'fas fa-images','admin_media')->setPermission("ROLE_REDACTEUR");
    }

    public function configureAssets(): Assets
    {
        return Assets::new()->addWebpackEncoreEntry('admin');
    }
}
