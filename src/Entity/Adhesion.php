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
    public const CHOICES = ['12' => 12, '26' => 26, '46' => 46];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    /**
     * @ORM\Column(type="datetime_immutable",options={"default":"CURRENT_TIMESTAMP"})
     */
    private $inscritedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Adherent::class, inversedBy="adhesions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $adherents;

    public function __construct() {
        $this->inscritedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getInscritedAt(): ?\DateTimeImmutable
    {
        return $this->inscritedAt;
    }

    /*public function setInscritedAt(\DateTimeImmutable $inscritedAt): self
    {
        $this->inscritedAt = $inscritedAt;

        return $this;
    }*/

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
        return $this->getInscritedAt()->format('d/m/Y') . ' (' . $this->getPrix() . '€)';
    }

}
