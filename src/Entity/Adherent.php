<?php

namespace App\Entity;

use App\Repository\AdherentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdherentRepository::class)
 */
class Adherent
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=SouscriptionAdhesion::class, mappedBy="adherents")
     */
    private $souscriptionAdhesions;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $CreatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Prenom;

    public function __construct()
    {
        $this->souscriptionAdhesions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|SouscriptionAdhesion[]
     */
    public function getSouscriptionAdhesions(): Collection
    {
        return $this->souscriptionAdhesions;
    }

    public function addSouscriptionAdhesion(SouscriptionAdhesion $souscriptionAdhesion): self
    {
        if (!$this->souscriptionAdhesions->contains($souscriptionAdhesion)) {
            $this->souscriptionAdhesions[] = $souscriptionAdhesion;
            $souscriptionAdhesion->setAdherents($this);
        }

        return $this;
    }

    public function removeSouscriptionAdhesion(SouscriptionAdhesion $souscriptionAdhesion): self
    {
        if ($this->souscriptionAdhesions->removeElement($souscriptionAdhesion)) {
            // set the owning side to null (unless already changed)
            if ($souscriptionAdhesion->getAdherents() === $this) {
                $souscriptionAdhesion->setAdherents(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeImmutable $CreatedAt): self
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function __toString()
    {
        return $this->getNom() + " " + $this->getPrenom();
    }
}
