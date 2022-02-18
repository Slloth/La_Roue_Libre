<?php

namespace App\Entity;

use App\Repository\SouscriptionAdhesionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SouscriptionAdhesionRepository::class)
 */
class SouscriptionAdhesion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $InscriptionAt;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="souscriptionAdhesions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $adherents;

    /**
     * @ORM\ManyToOne(targetEntity=Adhesion::class, inversedBy="souscriptionAdhesions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $adhesions;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInscriptionAt(): ?\DateTimeImmutable
    {
        return $this->InscriptionAt;
    }

    public function setInscriptionAt(\DateTimeImmutable $InscriptionAt): self
    {
        $this->InscriptionAt = $InscriptionAt;

        return $this;
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

    public function getAdhesions(): ?Adhesion
    {
        return $this->adhesions;
    }

    public function setAdhesions(?Adhesion $adhesions): self
    {
        $this->adhesions = $adhesions;

        return $this;
    }

    public function __toString()
    {
        return $this->getInscriptionAt()->format("d/m/Y");
    }
}
