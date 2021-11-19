<?php

namespace App\Service;

use App\Entity\Email;
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
        private MailerInterface $mailer,
        private EntityManagerInterface $em,
        private FlashBagInterface $flash,
        private UrlGeneratorInterface $router,
    )
    {
        $emailRepository;
        $newsletterRepository;
        $mailer;
        $em;
        $flash;
        $router;
    }
    /**
     * Enregistre un email en base de données d'un utilisateur pour nous
     *
     * @param FormInterface $form
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
                ->htmlTemplate("partial/__templatedEmail.html.twig");

            // pass variables
            if($mail->getEmailFrom() === $_ENV["EMAIL_ADDRESS"])
            {
                $email->context([
                    "body" => $mail->getContent(),
                    "unSubscribe" => $this->router->generate(
                        "newsletter_unsubscribe",
                        [
                            "id" => $this->newsletterRepository->findOneBy(["email" => $mail->getEmailTo()])->getId()
                        ],UrlGeneratorInterface::ABSOLUTE_URL)
                ]);
            }
            else
            {
                $email->context(["body" => $mail->getContent()]);    
            }
            $this->mailer->send($email);
            $mail->setIsSend(true);
            $this->em->flush($mail);
        }
        return $nbMails;
    }
}