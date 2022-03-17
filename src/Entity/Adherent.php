<?php

namespace App\Entity;

use App\Repository\AdherentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AdherentRepository::class)
 * @Table(name="adherents")
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
    private $fullName;

    /**
     * @ORM\Column(type="string", length=10)
     */
    #[Assert\Regex('/^((\+)33|0)[1-9](\d{2}){4}$/','Votre numéro de téléphone est invalide')]
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     */
    #[Assert\Email(null,'Votre email est invalide')]
    private $email;
    
    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    #[Assert\Regex('/^(([0-8][0-9])|(9[0-5]))[0-9]{3}$/','Votre code postal est invalide')]
    private $cp;

    /**
     * @ORM\Column(type="datetime_immutable", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Adhesion::class, mappedBy="adherents", orphanRemoval=true)
     */
    private $adhesions;

    public function __construct() {
        
        $this->createdAt = new \DateTimeImmutable();
        $this->adhesions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    
    public function getCp(): ?string
    {
        return $this->cp;
    }
    
    public function setCp(string $cp): self
    {
        $this->cp = $cp;
        
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
    
    /**
     * @return Collection|Adhesion[]
     */
    public function getAdhesions(): Collection
    {
        return $this->adhesions;
    }

    public function addAdhesion(Adhesion $adhesion): self
    {
        if (!$this->adhesions->contains($adhesion)) {
            $this->adhesions[] = $adhesion;
            $adhesion->setAdherents($this);
        }

        return $this;
    }

    public function removeAdhesion(Adhesion $adhesion): self
    {
        if ($this->adhesions->removeElement($adhesion)) {
            // set the owning side to null (unless already changed)
            if ($adhesion->getAdherents() === $this) {
                $adhesion->setAdherents(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getFullName();
    }
}
