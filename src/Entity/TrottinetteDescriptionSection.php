<?php

namespace App\Entity;

use App\Repository\TrottinetteDescriptionSectionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrottinetteDescriptionSectionRepository::class)]
class TrottinetteDescriptionSection
{
    #[ORM\Id, ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Trottinette::class, inversedBy: "descriptionSections")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Trottinette $trottinette = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $title = null;

    #[ORM\Column(type:"text")]
    private ?string $content = null;

    #[ORM\Column(type:"integer")]
    private int $sectionOrder = 0;

    // -------------------------------
    // Getters & Setters
    // -------------------------------
    public function getId(): ?int { return $this->id; }

    public function getTrottinette(): ?Trottinette { return $this->trottinette; }
    public function setTrottinette(?Trottinette $trottinette): self { $this->trottinette = $trottinette; return $this; }

    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): self { $this->title = $title; return $this; }

    public function getContent(): ?string { return $this->content; }
    public function setContent(string $content): self { $this->content = $content; return $this; }

    public function getSectionOrder(): int { return $this->sectionOrder; }
    public function setSectionOrder(int $sectionOrder): self { $this->sectionOrder = $sectionOrder; return $this; }
}
