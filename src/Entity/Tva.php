<?php

namespace App\Entity;

use App\Repository\TvaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TvaRepository::class)]
class Tva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null; // ex: "Taux normal 20%"

    #[ORM\Column(type: "float")]
    private ?float $value = null; // ex: 0.20

    // --- Relations ---
    #[ORM\OneToMany(mappedBy: "tva", targetEntity: Trottinette::class)]
    private Collection $trottinettes;

    #[ORM\OneToMany(mappedBy: "tva", targetEntity: Accessory::class)]
    private Collection $accessories;

    public function __construct()
    {
        $this->trottinettes = new ArrayCollection();
        $this->accessories = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    // ------------------- GETTERS & SETTERS -------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;
        return $this;
    }

    // -------- Relations avec Trottinette --------
    /**
     * @return Collection<int, Trottinette>
     */
    public function getTrottinettes(): Collection
    {
        return $this->trottinettes;
    }

    public function addTrottinette(Trottinette $trottinette): self
    {
        if (!$this->trottinettes->contains($trottinette)) {
            $this->trottinettes->add($trottinette);
            $trottinette->setTva($this);
        }
        return $this;
    }

    public function removeTrottinette(Trottinette $trottinette): self
    {
        if ($this->trottinettes->removeElement($trottinette)) {
            if ($trottinette->getTva() === $this) {
                $trottinette->setTva(null);
            }
        }
        return $this;
    }

    // -------- Relations avec Accessory --------
    /**
     * @return Collection<int, Accessory>
     */
    public function getAccessories(): Collection
    {
        return $this->accessories;
    }

    public function addAccessory(Accessory $accessory): self
    {
        if (!$this->accessories->contains($accessory)) {
            $this->accessories->add($accessory);
            $accessory->setTva($this);
        }
        return $this;
    }

    public function removeAccessory(Accessory $accessory): self
    {
        if ($this->accessories->removeElement($accessory)) {
            if ($accessory->getTva() === $this) {
                $accessory->setTva(null);
            }
        }
        return $this;
    }
}
