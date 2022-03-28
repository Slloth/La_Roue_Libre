<?php

namespace App\Controller\Admin;

use App\Form\NewsletterType;
use App\Service\EmailService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailController extends AbstractController
{
    #[Route('/admin/mail/newsletter', name: 'admin_mail_newsletter')]
    public function newsletter(Request $request, EmailService $emailService): Response
    {
        $form = $this->createForm(NewsletterType::class);

        $form->get("emailFrom")->setData($_ENV["EMAIL_ADDRESS"]);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $emailService->persistEmailForNewsletter($form);
            return $this->redirectToRoute("admin");
        }
        return $this->render('admin/mail/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/mail/adherent', name: 'admin_mail_adherent')]
    public function adherent(Request $request, EmailService $emailService): Response
    {
        //dd(new DateTime("2022-03-28 +2 year"));
        $form = $this->createForm(NewsletterType::class);

        $form->get("emailFrom")->setData($_ENV["EMAIL_ADDRESS"]);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $emailService->persistEmailForAdherents($form);
            return $this->redirectToRoute("admin");
        }
        return $this->render('admin/mail/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
