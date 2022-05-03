<?php

namespace App\Entity;

use App\Repository\AdhesionRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

/**
 * @ORM\Entity(repositoryClass=AdhesionRepository::class)
 * @Table(name="adhesions")
 */
class Adhesion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $prix;
    
    /**
     * @ORM\Column(type="datetime_immutable",options={"default":"CURRENT_TIMESTAMP"})
     */
    private $subscribedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="adhesions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $adherents;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $branch;

    public function __construct() {
        $this->subscribedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getSubscribedAt(): ?\DateTimeImmutable
    {
        return $this->subscribedAt;
    }

    public function getAdherents(): ?Adherent
    {
        return $this->adherents;
    }

    public function setAdherents(?Adherent $adherents): self
    {
        $this->adherents = $adherents;

        return $this;
    }

    public function __toString()
    {
        return $this->getSubscribedAt()->format('d/m/Y') . ' (' . $this->getPrix() . '€) à '. $this->getBranch();
    }

    public function getBranch(): ?string
    {
        return $this->branch;
    }

    public function setBranch(string $branch): self
    {
        $this->branch = $branch;

        return $this;
    }

}
