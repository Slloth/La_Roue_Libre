<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * Affiche le formulaire de contact
     *
     * @param Request $request
     * @param EmailService $emailService
     * 
     * @return Response
     */
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, EmailService $emailService): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $emailService->persistEmailForUs($form);
            return $this->redirectToRoute("contact");
        }
        $currentURL = "contact";
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
            'currentURL' => $currentURL,
            'red' => "#f04f3e"
        ]);
    }
}
