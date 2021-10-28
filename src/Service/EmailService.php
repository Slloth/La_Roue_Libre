<?php

namespace App\Service;

use App\Entity\Email;
use App\Repository\EmailRepository;
use App\Repository\NewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class EmailService
{
    private $host;
    public function __construct
    (
        private EmailRepository $emailRepository,
        private NewsletterRepository $newsletterRepository,
        private MailerInterface $mailer,
        private EntityManagerInterface $em,
        private FlashBagInterface $flash,
        private UrlGeneratorInterface $urlGenerator,
    )
    {
        $emailRepository;
        $newsletterRepository;
        $mailer;
        $em;
        $flash;
        $urlGenerator;
    }
    /**
     * Enregistre un email en base de données d'un utilisateur pour nous
     *
     * @param FormInterface $form
     * @return void
     */
    public function persistEmail(FormInterface $form,string $host): void
    {
        $this->host = $host;
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
                ->htmlTemplate("partial/__templatedEmail.html.twig")
                // pass variables
                ->context(["body" => $mail->getContent()]);

            if($mail->getEmailFrom() === $mail->getEmailTo()){

                foreach($this->newsletterRepository->findBy(["isVerify" => true]) as $newsletter)
                {
                    $email  ->addBcc($newsletter->getEmail());
                }
            }
            $this->mailer->send($email);
            $mail->setIsSend(true);
            $this->em->flush($mail);
        }
        return $nbMails;
    }
}