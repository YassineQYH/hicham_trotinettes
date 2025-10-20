<?php

namespace App\Entity;

use App\Repository\OrderDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDetailsRepository::class)]
class OrderDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'orderDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $myOrder = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $product = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantity = null;

    #[ORM\Column(type: 'float')]
    private ?float $price = null;

    #[ORM\Column(type: 'float')]
    private ?float $total = null;

    #[ORM\Column(type: 'string', length: 64)]
    private ?string $weight = null;

    public function __toString(): string
    {
        return $this->getProduct().' x'.$this->getQuantity().' - Taille '.$this->getWeight();
    }

    public function getId(): ?int { return $this->id; }

    public function getMyOrder(): ?Order { return $this->myOrder; }
    public function setMyOrder(?Order $myOrder): self { $this->myOrder = $myOrder; return $this; }

    public function getProduct(): ?string { return $this->product; }
    public function setProduct(string $product): self { $this->product = $product; return $this; }

    public function getQuantity(): ?int { return $this->quantity; }
    public function setQuantity(int $quantity): self { $this->quantity = $quantity; return $this; }

    public function getPrice(): ?float { return $this->price; }
    public function setPrice(float $price): self { $this->price = $price; return $this; }

    public function getTotal(): ?float { return $this->total; }
    public function setTotal(float $total): self { $this->total = $total; return $this; }

    public function getWeight(): ?string { return $this->weight; }
    public function setWeight(string $weight): self { $this->weight = $weight; return $this; }
}
