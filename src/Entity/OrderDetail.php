<?php

namespace App\Entity;

use App\Repository\OrderDetailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDetailRepository::class)]
class OrderDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $quantity;

    #[ORM\Column(type: 'float')]
    private $priceEach;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'details')]
    #[ORM\JoinColumn(nullable: false)]
    private $orderObj;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'orderDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private $product;

    #[ORM\ManyToMany(targetEntity: OptionChoice::class, inversedBy: 'orderDetails')]
    private $optionChoices;

    public function __construct()
    {
        $this->optionChoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPriceEach(): ?float
    {
        return $this->priceEach;
    }

    public function setPriceEach(float $priceEach): self
    {
        $this->priceEach = $priceEach;

        return $this;
    }

    public function getOrderObj(): ?Order
    {
        return $this->orderObj;
    }

    public function setOrderObj(?Order $orderObj): self
    {
        $this->orderObj = $orderObj;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Collection<int, OptionChoice>
     */
    public function getOptionChoices(): Collection
    {
        return $this->optionChoices;
    }

    public function addOptionChoice(OptionChoice $optionChoice): self
    {
        if (!$this->optionChoices->contains($optionChoice)) {
            $this->optionChoices[] = $optionChoice;
        }

        return $this;
    }

    public function removeOptionChoice(OptionChoice $optionChoice): self
    {
        $this->optionChoices->removeElement($optionChoice);

        return $this;
    }
}
