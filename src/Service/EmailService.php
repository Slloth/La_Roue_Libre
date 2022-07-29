<?php

namespace App\Service;

use App\Entity\Email;
use App\Repository\AdherentRepository;
use App\Repository\EmailRepository;
use App\Repository\NewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailService
{
    public function __construct
    (
        private EmailRepository $emailRepository,
        private NewsletterRepository $newsletterRepository,
        private AdherentRepository $adherentRepository,
        private MailerInterface $mailer,
        private EntityManagerInterface $em,
        private FlashBagInterface $flash,
        private UrlGeneratorInterface $router,
    )
    {}

    /**
     * Enregistre un email en base de données d'un utilisateur pour les administrateur du site
     *
     * @param FormInterface $form
     * 
     * @return void
     */
    public function persistEmailForUs(FormInterface $form): void
    {
        $email = new Email();

        $email  ->setEmailFrom($form->get("emailFrom")->getData())
                ->setEmailTo($_ENV["EMAIL_ADDRESS"])
                ->setSubject($form->get("subject")->getData())
                ->setContent($form->get("content")->getData())
                ->setIsSend(false);

        $this->em->persist($email);
        $this->em->flush();
        $this->flash->add("success","Votre email à bien été envoyé");
    }

    /**
     * Enregistre un email en base de données d'un utilisateur pour les inscrit à la newsletter
     *
     * @param FormInterface $form
     * 
     * @return void
     */
    public function persistEmailForNewsletter(FormInterface $form): void
    {
        foreach($this->newsletterRepository->findBy(["isVerify" => true]) as $newsletteEmail)
        {
            $email = new Email();

            $email  ->setEmailFrom($_ENV["EMAIL_ADDRESS"])
                    ->setEmailTo($newsletteEmail->getEmail())
                    ->setSubject($form->get("subject")->getData())
                    ->setContent($form->get("content")->getData())
                    ->setIsSend(false);

            $this->em->persist($email);
            $this->em->flush();
        }
        $this->flash->add("success","Votre Newsletter à bien été Enregistré, elle sera envoyé à Minuit.");
    }

    /**
     * Enregistre un email en base de données d'un utilisateur pour les adhérents
     *
     * @param FormInterface $form
     * 
     * @return void
     */
    public function persistEmailForAdherents(FormInterface $form): void
    {
        foreach($this->adherentRepository->findCurrentsAdherentsForEmail() as $adherentEmail)
        {
            $email = new Email();

            $email  ->setEmailFrom($_ENV["EMAIL_ADDRESS"])
                    ->setEmailTo($adherentEmail->getEmail())
                    ->setSubject($form->get("subject")->getData())
                    ->setContent($form->get("content")->getData())
                    ->setIsSend(false);

            $this->em->persist($email);
            $this->em->flush();
        }
        $this->flash->add("success","Le mail pour les adherents à bien été Enregistré, elle sera envoyé à Minuit.");
    }

    /**
     * Crée un email pour chaque mails enregistrer en base de données puis l'envoi et passe la valeur send à vrai en base de données
     *
     * @param integer|null $limitMessage
     * 
     * @return integer
     */
    public function sendEmail(int $limitMessage = null): int
    {
        $mails = $this->emailRepository->findBy(["isSend" => false],[],$limitMessage);
        $nbMails = count($mails);

        if(empty($mails)){
           throw new CommandNotFoundException("Aucun email n'est a envoyer !");
        }

        foreach($mails as $mail)
        {
            $email = (new TemplatedEmail())
                ->from($mail->getEmailFrom())
                ->to($mail->getEmailTo())
                ->subject($mail->getSubject())
            ;
            
            $newsletters = $this->newsletterRepository->findOneBy(["email" => $mail->getEmailTo()]);
            // pass variables
            if ($newsletters)
            {
                if($mail->getEmailFrom() === $_ENV["EMAIL_ADDRESS"] && $newsletters->getId() != null)
                {
                    $email->htmlTemplate("partial/__templatedEmailNewsletter.html.twig");
                    $email->context([
                        "body" => $mail->getContent(),
                        "unSubscribe" => $this->router->generate(
                            "newsletter_unsubscribe",
                            [
                                "id" => $newsletters->getId()
                            ],UrlGeneratorInterface::ABSOLUTE_URL)
                    ]);
                }
            }
            else
            {
                $email->htmlTemplate("partial/__templatedEmailAdherent.html.twig");
                $email->context(["body" => $mail->getContent()]);    
            }
            $this->mailer->send($email);
            $mail->setIsSend(true);
            $this->em->flush($mail);
        }
        return $nbMails;
    }
}