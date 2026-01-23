<?php

namespace App\Entity;

use App\Repository\CategorieCaracteristiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieCaracteristiqueRepository::class)]
class CategorieCaracteristique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $name = null;

    // ✅ Une catégorie possède plusieurs caractéristiques
    #[ORM\OneToMany(mappedBy: "categorie", targetEntity: Caracteristique::class)]
    private Collection $caracteristiques;

    public function __construct()
    {
        $this->caracteristiques = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    /** @return Collection<int, Caracteristique> */
    public function getCaracteristiques(): Collection
    {
        return $this->caracteristiques;
    }

    public function addCaracteristique(Caracteristique $caracteristique): self
    {
        if (!$this->caracteristiques->contains($caracteristique)) {
            $this->caracteristiques[] = $caracteristique;
            $caracteristique->setCategorie($this);
        }
        return $this;
    }

    public function removeCaracteristique(Caracteristique $caracteristique): self
    {
        if ($this->caracteristiques->removeElement($caracteristique)) {
            if ($caracteristique->getCategorie() === $this) {
                $caracteristique->setCategorie(null);
            }
        }
        return $this;
    }
}
