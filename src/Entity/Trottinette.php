<?php

namespace App\Entity;

use App\Repository\TrottinetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Accessory;

#[ORM\Entity(repositoryClass: TrottinetteRepository::class)]
class Trottinette
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $name = null;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $nameShort = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $slug = null;

    #[ORM\Column(type:"text")]
    private ?string $description = null;

    #[ORM\Column(type:"text", nullable:true)]
    private ?string $descriptionShort = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $image = null;

    #[ORM\Column(type:"boolean")]
    private ?bool $isBest = null;

    // ---- NOUVEAUX CHAMPS POUR LE CARROUSEL ----
    #[ORM\Column(type:"boolean")]
    private bool $isHeader = false;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $headerImage = null;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $headerBtnTitle = null;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $headerBtnUrl = null;

    // ✅ Relation avec TrottinetteCaracteristique (pivot)
    #[ORM\OneToMany(mappedBy: "trottinette", targetEntity: TrottinetteCaracteristique::class, cascade: ["persist", "remove"])]
    private Collection $trottinetteCaracteristiques;

    // ✅ Relation ManyToMany avec Accessory
    #[ORM\ManyToMany(targetEntity: Accessory::class, inversedBy: "trottinettes")]
    #[ORM\JoinTable(name: "trottinette_accessory")]
    private Collection $accessories;

    public function __construct()
    {
        $this->trottinetteCaracteristiques = new ArrayCollection();
        $this->accessories = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    // ---- GETTERS & SETTERS ----
    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getNameShort(): ?string { return $this->nameShort; }
    public function setNameShort(?string $nameShort): self { $this->nameShort = $nameShort; return $this; }

    public function getSlug(): ?string { return $this->slug; }
    public function setSlug(string $slug): self { $this->slug = $slug; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }

    public function getDescriptionShort(): ?string { return $this->descriptionShort; }
    public function setDescriptionShort(?string $descriptionShort): self { $this->descriptionShort = $descriptionShort; return $this; }

    public function getImage(): ?string { return $this->image; }
    public function setImage(string $image): self { $this->image = $image; return $this; }

    public function getIsBest(): ?bool { return $this->isBest; }
    public function setIsBest(bool $isBest): self { $this->isBest = $isBest; return $this; }

    public function getIsHeader(): bool { return $this->isHeader; }
    public function setIsHeader(bool $isHeader): self { $this->isHeader = $isHeader; return $this; }

    public function getHeaderImage(): ?string { return $this->headerImage; }
    public function setHeaderImage(?string $headerImage): self { $this->headerImage = $headerImage; return $this; }

    public function getHeaderBtnTitle(): ?string { return $this->headerBtnTitle; }
    public function setHeaderBtnTitle(?string $headerBtnTitle): self { $this->headerBtnTitle = $headerBtnTitle; return $this; }

    public function getHeaderBtnUrl(): ?string { return $this->headerBtnUrl; }
    public function setHeaderBtnUrl(?string $headerBtnUrl): self { $this->headerBtnUrl = $headerBtnUrl; return $this; }

    /** @return Collection<int, TrottinetteCaracteristique> */
    public function getTrottinetteCaracteristiques(): Collection { return $this->trottinetteCaracteristiques; }
    public function addTrottinetteCaracteristique(TrottinetteCaracteristique $tc): self
    {
        if (!$this->trottinetteCaracteristiques->contains($tc)) {
            $this->trottinetteCaracteristiques[] = $tc;
            $tc->setTrottinette($this);
        }
        return $this;
    }
    public function removeTrottinetteCaracteristique(TrottinetteCaracteristique $tc): self
    {
        if ($this->trottinetteCaracteristiques->removeElement($tc)) {
            if ($tc->getTrottinette() === $this) {
                $tc->setTrottinette(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Accessory> */
    public function getAccessories(): Collection { return $this->accessories; }
    public function addAccessory(Accessory $accessory): self
    {
        if (!$this->accessories->contains($accessory)) {
            $this->accessories->add($accessory);
            $accessory->addTrottinette($this); // ⚡ cohérence inverse
        }
        return $this;
    }
    public function removeAccessory(Accessory $accessory): self
    {
        if ($this->accessories->removeElement($accessory)) {
            $accessory->removeTrottinette($this); // ⚡ cohérence inverse
        }
        return $this;
    }
}
