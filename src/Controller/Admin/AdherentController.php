<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdherentController extends AbstractController
{
    #[Route('/admin/adherent', name: 'admin_adherent')]
    public function index(): Response
    {
        return $this->render('admin/adherent/index.html.twig', [
            'controller_name' => 'AdherentController',
        ]);
    }
}
