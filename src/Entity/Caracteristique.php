<?php

namespace App\Entity;

use App\Repository\CaracteristiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaracteristiqueRepository::class)]
class Caracteristique
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $name = null;

    // ✅ Relation avec TrottinetteCaracteristique (pivot)
    #[ORM\OneToMany(mappedBy: "caracteristique", targetEntity: TrottinetteCaracteristique::class, cascade: ["persist", "remove"])]
    private Collection $trottinetteCaracteristiques;

    public function __construct()
    {
        $this->trottinetteCaracteristiques = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    /** @return Collection<int, TrottinetteCaracteristique> */
    public function getTrottinetteCaracteristiques(): Collection
    {
        return $this->trottinetteCaracteristiques;
    }

    public function addTrottinetteCaracteristique(TrottinetteCaracteristique $tc): self
    {
        if (!$this->trottinetteCaracteristiques->contains($tc)) {
            $this->trottinetteCaracteristiques[] = $tc;
            $tc->setCaracteristique($this);
        }
        return $this;
    }

    public function removeTrottinetteCaracteristique(TrottinetteCaracteristique $tc): self
    {
        if ($this->trottinetteCaracteristiques->removeElement($tc)) {
            if ($tc->getCaracteristique() === $this) {
                $tc->setCaracteristique(null);
            }
        }
        return $this;
    }
}
