<?php

namespace App\Controller\Admin;

use App\Form\MediaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaCrudController extends AbstractController
{
    #[Route('/admin/media/crud', name: 'admin_media_crud')]
    public function index(): Response
    {
        $form = $this->createForm(MediaType::class);

        return $this->render('admin/media_crud/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
