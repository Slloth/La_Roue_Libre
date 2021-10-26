<?php

namespace App\Controller\Admin;

use App\Form\MediaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    #[Route('/admin/media', name: 'admin_media')]
    public function index(): Response
    {
        $form = $this->createForm(MediaType::class);
        
        return $this->render('admin/media/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
