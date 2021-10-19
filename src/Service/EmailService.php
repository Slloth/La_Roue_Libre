<?php

namespace App\Service;

use App\Entity\Email;
use App\Repository\EmailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;

class EmailService 
{
    public function __construct
    (
        private EmailRepository $emailRepository,
        private MailerInterface $mailer,
        private EntityManagerInterface $em,
        private FlashBagInterface $flash
    )
    {
        $emailRepository;
        $mailer;
        $em;
        $flash;
    }
    /**
     * Enregistre un email en base de données d'un utilisateur pour nous
     *
     * @param FormInterface $form
     * @return void
     */
    public function emailToUs(FormInterface $form): void
    {
        $email = $this->createEmail($form);
        $email  ->setToEmail($_ENV["EMAIL_SITE"])
                ->setSubject("Contact")
                ->setFromEmail($form->get("fromEmail")->getData());
        $this->persistEmail($email);
    }

    /**
     * Enregistre un email en base de données pour les utilisateurs inscrits par nous
     *
     * @param FormInterface $form
     * @return void
     */
    public function emailToContact(FormInterface $form): void
    {
        $email = $this->createEmail($form);
        $email  ->setFromEmail($form->get("fromEmail")->getData())
                ->setSubject("")
                ->setToEmail($_ENV["EMAIL_SITE"]);
        $this->persistEmail($email);
    }

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
                ->from($mail->getFromEmail())
                ->to($mail->getToEmail())
                ->subject($mail->getSubject())
                ->htmlTemplate("partial/__templatedEmail.html.twig")
                // pass variables
                ->context(["body" => $mail->getContent()]);

            $this->mailer->send($email);
            $mail->setIsSend(true);
            $this->em->flush($mail);
        }
        return $nbMails;
    }

    /**
     * Créer le corps du mail
     *
     * @param FormInterface $form
     * @return Email
     */
    private function createEmail(FormInterface $form): Email
    {
        $email = new Email();
        $email  ->setContent($form->get("content")->getData())
                ->setIsSend(false);
        return $email;
    }

    /**
     * Persiste le mail en base de données
     *
     * @param Email $email
     * @return void
     */
    private function persistEmail(Email $email): void
    {
        $this->em->persist($email);
        $this->em->flush();
        $this->flash->add("success","Votre email à bien été envoyé");
    }
}