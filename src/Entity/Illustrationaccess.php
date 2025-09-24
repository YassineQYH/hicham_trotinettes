<?php

namespace App\Entity;

use App\Repository\IllustrationaccessRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IllustrationaccessRepository::class)]
class Illustrationaccess
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:255)]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: Accessory::class, inversedBy:"illustrationaccess")]
    #[ORM\JoinColumn(nullable:false)]
    private ?Accessory $accessory = null;

    public function __toString(): string { return $this->image ?? ''; }

    public function getId(): ?int { return $this->id; }
    public function getImage(): ?string { return $this->image; }
    public function setImage(string $image): self { $this->image = $image; return $this; }

    public function getAccessory(): ?Accessory { return $this->accessory; }
    public function setAccessory(?Accessory $accessory): self { $this->accessory = $accessory; return $this; }
}
