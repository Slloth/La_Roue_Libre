<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\SubscribeType;
use App\Repository\NewsletterRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

class SubscribeNewsletterController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em ,private NewsletterRepository $newsletterRepository)
    {
        $em;
        $newsletterRepository;
    }

    #[Route('/inscription_newsletter', name: 'newsletter_register')]
    public function register(Request $request, MailerInterface $mailer): Response
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(SubscribeType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $newsletter->setEmail($form->get('email')->getData());
            $newsletter->setIsVerify(false);

            $this->em = $this->getDoctrine()->getManager();
            try{
                $this->em->persist($newsletter);
                $this->em->flush();
            }
            catch(UniqueConstraintViolationException $e){
                $this->addFlash('danger', 'Cette Email est déjà inscrit à la newsletter.');
                return $this->redirectToRoute('home');
            }
            $mailer->send((new TemplatedEmail())
                    ->from(new Address($_ENV["EMAIL_ADDRESS"], 'Mail Bot'))
                    ->to($newsletter->getEmail())
                    ->subject('Please Confirm your Email')
                    ->context([
                        'signedUrl' => $request->getSchemeAndHttpHost().$this->generateUrl(
                            "newsletter_verify_email",
                            ["id" => $this->newsletterRepository->findOneBy([
                                "email" => $form->get('email')->getData()]
                                )->getId(),
                            ])
                    ])
                    ->htmlTemplate('newsletter/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Un email de confimation à été envoyé sur votre boite mail.');
            return $this->redirectToRoute('home');
        }

        return $this->render('newsletter/__form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/newsletter/verify/email', name: 'newsletter_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        // validate email confirmation link, sets User::isVerified=true and persists

        $newsletter = $this->newsletterRepository->find($request->get("id"));
        $newsletter->setIsVerify(true);
        $this->em->flush();

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre email à été vérifié avec succès.');

        return $this->redirectToRoute('home');
    }

    #[Route('/newsletter/unsubscribe/{id}', name: 'newsletter_unsubscribe')]
    public function unSubscribe($id){
        $emailNewsletter = $this->newsletterRepository->find($id);
        $this->em->remove($emailNewsletter);
        $this->em->flush();
        $this->addFlash("warning","Votre adresse email : ".$emailNewsletter->getEmail()." à bien été désinscrit de la newsletter");
        return $this->redirectToRoute('home');
    }
}
