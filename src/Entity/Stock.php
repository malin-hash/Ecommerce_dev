<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nameproduct = null;

    #[ORM\Column]
    private ?int $quantityproduct = null;

    #[ORM\Column]
    private ?int $priceproduct = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateaddproduct = null;

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

    public function getQuantityproduct(): ?int
    {
        return $this->quantityproduct;
    }

    public function setQuantityproduct(int $quantityproduct): static
    {
        $this->quantityproduct = $quantityproduct;

        return $this;
    }

    public function getPriceproduct(): ?int
    {
        return $this->priceproduct;
    }

    public function setPriceproduct(int $priceproduct): static
    {
        $this->priceproduct = $priceproduct;

        return $this;
    }

    public function getDateaddproduct(): ?\DateTimeImmutable
    {
        return $this->dateaddproduct;
    }

    public function setDateaddproduct(\DateTimeImmutable $dateaddproduct): static
    {
        $this->dateaddproduct = $dateaddproduct;

        return $this;
    }
}
