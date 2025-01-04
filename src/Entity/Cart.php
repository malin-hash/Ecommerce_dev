<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nameproduct = null;

    #[ORM\Column(length: 255)]
    private ?string $descriptionproduct = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $nameuser = null;

    #[ORM\Column(length: 255)]
    private ?string $FeaturedImage = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNameproduct(): ?string
    {
        return $this->nameproduct;
    }

    public function setNameproduct(string $nameproduct): static
    {
        $this->nameproduct = $nameproduct;

        return $this;
    }

    public function getDescriptionproduct(): ?string
    {
        return $this->descriptionproduct;
    }

    public function setDescriptionproduct(string $descriptionproduct): static
    {
        $this->descriptionproduct = $descriptionproduct;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getNameuser(): ?User
    {
        return $this->nameuser;
    }

    public function setNameuser(?User $nameuser): static
    {
        $this->nameuser = $nameuser;

        return $this;
    }

    public function getFeaturedImage(): ?string
    {
        return $this->FeaturedImage;
    }

    public function setFeaturedImage(string $FeaturedImage): static
    {
        $this->FeaturedImage = $FeaturedImage;

        return $this;
    }
}
