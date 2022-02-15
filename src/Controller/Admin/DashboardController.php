<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Newsletter;
use App\Entity\Page;
use App\Entity\User;
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
        yield MenuItem::section("Administration","fas fa-users-cog");
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-users', User::class)->setPermission("ROLE_ADMIN");
        yield MenuItem::section("Contenu","fas fa-blog");
        yield MenuItem::linkToCrud('Newsletter', 'fas fa-users', Newsletter::class);
        yield MenuItem::linkToCrud('Page', 'fas fa-columns', Page::class);
        yield MenuItem::linkToCrud('Article', 'fas fa-newspaper', Article::class);
        yield MenuItem::linkToCrud('Categorie', 'fas fa-tag', Category::class);
        yield MenuItem::linkToCrud('Commentaire', 'fas fa-comments', Comment::class);
        yield MenuItem::linkToRoute('Envrionnement', 'fas fa-cogs', 'admin_env');
        yield MenuItem::section("Application","fas fa-window-maximize");
        yield MenuItem::linkToRoute("Envoie d'email", 'fas fa-envelope', 'admin_mail');
        yield MenuItem::linkToRoute('MultiMédias', 'fas fa-images','admin_media');
    }
}
