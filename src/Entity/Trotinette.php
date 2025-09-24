<?php

namespace App\Entity;

use App\Repository\TrotinetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ModelTrotinette;
use App\Entity\Illustration;

#[ORM\Entity(repositoryClass: TrotinetteRepository::class)]
class Trotinette
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $name = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $slug = null;

    #[ORM\Column(type:"text")]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: ModelTrotinette::class, inversedBy:"trotinettes")]
    #[ORM\JoinColumn(nullable:false)]
    private ?ModelTrotinette $modelTrotinette = null;

    #[ORM\OneToMany(mappedBy:"trotinette", targetEntity: Illustration::class)]
    private Collection $illustration;

    #[ORM\Column(type:"string", length:255)]
    private ?string $image = null;

    #[ORM\Column(type:"boolean")]
    private ?bool $isBest = null;

    public function __construct()
    {
        $this->illustration = new ArrayCollection();
    }

    public function __toString(): string { return $this->name ?? ''; }

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getSlug(): ?string { return $this->slug; }
    public function setSlug(string $slug): self { $this->slug = $slug; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): self { $this->description = $description; return $this; }

    public function getModelTrotinette(): ?ModelTrotinette { return $this->modelTrotinette; }
    public function setModelTrotinette(?ModelTrotinette $modelTrotinette): self { $this->modelTrotinette = $modelTrotinette; return $this; }

    /** @return Collection|Illustration[] */
    public function getIllustration(): Collection { return $this->illustration; }
    public function addIllustration(Illustration $illustration): self
    {
        if (!$this->illustration->contains($illustration)) {
            $this->illustration[] = $illustration;
            $illustration->setTrotinette($this);
        }
        return $this;
    }
    public function removeIllustration(Illustration $illustration): self
    {
        if ($this->illustration->removeElement($illustration)) {
            if ($illustration->getTrotinette() === $this) {
                $illustration->setTrotinette(null);
            }
        }
        return $this;
    }

    public function getImage(): ?string { return $this->image; }
    public function setImage(string $image): self { $this->image = $image; return $this; }

    public function getIsBest(): ?bool { return $this->isBest; }
    public function setIsBest(bool $isBest): self { $this->isBest = $isBest; return $this; }
}
