<?php

namespace App\Entity;

use App\Repository\EmailRepository;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EmailRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @Table(name="emails")
 */
class Email
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\Email(
        message: 'L\'email : {{ value }} n\'est pas un email valide',
    )]
    private $emailTo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\Email(
        message: 'L\'email : {{ value }} n\'est pas un email valide',
    )]
    private $emailFrom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Votre titre doit contenir au minimum {{ limit }} caractères',
        maxMessage: 'Votre titre doit contenir au maximum {{ limit }} caractères',
    )]
    private $subject;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSend;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmailTo(): ?string
    {
        return $this->emailTo;
    }

    public function setEmailTo(string $emailTo): self
    {
        $this->emailTo = $emailTo;

        return $this;
    }

    public function getEmailFrom(): ?string
    {
        return $this->emailFrom;
    }

    public function setEmailFrom(string $emailFrom): self
    {
        $this->emailFrom = $emailFrom;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIsSend(): ?bool
    {
        return $this->isSend;
    }

    public function setIsSend(bool $isSend): self
    {
        $this->isSend = $isSend;

        return $this;
    }
    /**
    * @ORM\PrePersist
    *
    * @return void
    */
    public function onPrePresist(){
        $this->createdAt = new \DateTimeImmutable();
    }
}
