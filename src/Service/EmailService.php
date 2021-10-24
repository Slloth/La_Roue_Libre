<?php

namespace App\Service;

use App\Entity\Email;
use App\Repository\EmailRepository;
use App\Repository\UserRepository;
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
        private UserRepository $userRepository,
        private MailerInterface $mailer,
        private EntityManagerInterface $em,
        private FlashBagInterface $flash
    )
    {
        $emailRepository;
        $userRepository;
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
    public function persistEmail(FormInterface $form): void
    {
        $email = new Email();

        $email  ->setEmailFrom($form->get("emailFrom")->getData())
                ->setEmailTo($_ENV["EMAIL_SITE"])
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

                foreach($this->userRepository->findBy(["isSubscribedToNewsletter" => true]) as $test)
                {
                    $email->addBcc($test->getEmail());
                }
                //dd($email);
            }
            $this->mailer->send($email);
            $mail->setIsSend(true);
            $this->em->flush($mail);
        }
        return $nbMails;
    }
}