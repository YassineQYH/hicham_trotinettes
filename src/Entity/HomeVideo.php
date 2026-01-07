<?php

namespace App\Entity;

use App\Repository\HomeVideoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HomeVideoRepository::class)]
class HomeVideo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Titre interne (admin uniquement)
     */
    #[ORM\Column(length: 255)]
    private string $title;

    /**
     * Nom du fichier vidéo uploadé
     * ex: home-video-64ff2c8a.mp4
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $videoFile = null;

    /**
     * URL vidéo externe (YouTube / Vimeo)
     * ex: https://www.youtube.com/embed/xxxx
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $videoUrl = null;

    /**
     * Texte principal affiché sur la vidéo
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $headline = null;

    /**
     * Sous-texte affiché sous le headline
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subtitle = null;

    /**
     * Active / désactive la vidéo
     */
    #[ORM\Column]
    private bool $isActive = true;

    /**
     * Ordre d'affichage (si plusieurs vidéos plus tard)
     */
    #[ORM\Column(nullable: true)]
    private ?int $position = 1;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    /* ===================== */
    /* ===== GETTERS ======= */
    /* ===================== */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getVideoFile(): ?string
    {
        return $this->videoFile;
    }

    public function getVideoUrl(): ?string
    {
        return $this->videoUrl;
    }

    public function getHeadline(): ?string
    {
        return $this->headline;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /* ===================== */
    /* ===== SETTERS ======= */
    /* ===================== */

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setVideoFile(?string $videoFile): self
    {
        $this->videoFile = $videoFile;

        return $this;
    }

    public function setVideoUrl(?string $videoUrl): self
    {
        $this->videoUrl = $videoUrl;

        return $this;
    }

    public function setHeadline(?string $headline): self
    {
        $this->headline = $headline;

        return $this;
    }

    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
