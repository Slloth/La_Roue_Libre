<?php

namespace App\Controller\Admin;

use App\Form\ContactType;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    #[Route('/admin/mail', name: 'admin_mail')]
    public function index(Request $request, EmailService $emailService): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->get("emailFrom")->setData($_ENV["EMAIL_SITE"]);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $emailService->persistEmail($form);
            return $this->redirectToRoute("admin");
        }
        return $this->render('admin/mail/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
