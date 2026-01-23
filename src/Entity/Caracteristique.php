<?php

namespace App\Entity;

use App\Repository\CaracteristiqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaracteristiqueRepository::class)]
class Caracteristique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $name = null;

    // ✅ UNE catégorie par caractéristique
    #[ORM\ManyToOne(targetEntity: CategorieCaracteristique::class, inversedBy: "caracteristiques")]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategorieCaracteristique $categorie = null;

    // ✅ Relation avec la table pivot trottinette
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

    public function getCategorie(): ?CategorieCaracteristique
    {
        return $this->categorie;
    }

    public function setCategorie(?CategorieCaracteristique $categorie): self
    {
        $this->categorie = $categorie;
        return $this;
    }

    /** @return Collection<int, TrottinetteCaracteristique> */
    public function getTrottinetteCaracteristiques(): Collection
    {
        return $this->trottinetteCaracteristiques;
    }
}
