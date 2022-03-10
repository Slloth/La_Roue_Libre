<?php

namespace App\Entity;

use App\Repository\AdhesionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdhesionRepository::class)
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
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=SouscriptionAdhesion::class, mappedBy="adhesions")
     */
    private $souscriptionAdhesions;

    public function __construct()
    {
        $this->souscriptionAdhesions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
            $souscriptionAdhesion->setAdhesions($this);
        }

        return $this;
    }

    public function removeSouscriptionAdhesion(SouscriptionAdhesion $souscriptionAdhesion): self
    {
        if ($this->souscriptionAdhesions->removeElement($souscriptionAdhesion)) {
            // set the owning side to null (unless already changed)
            if ($souscriptionAdhesion->getAdhesions() === $this) {
                $souscriptionAdhesion->setAdhesions(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getType();
    }
}
