<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
	paginationItemsPerPage: 10,
	normalizationContext: ["groups" => ["read:Product"]],
	denormalizationContext: ["groups" => ["write:Product"]]
)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
	#[Groups(["read:Product", "read:Order"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
	#[Groups(["read:Product", "read:Order", "write:Product"])]
    private $name;

    #[ORM\Column(type: 'text', nullable: true)]
	#[Groups(["read:Product", "read:Order", "write:Product"])]
    private $description;

    #[ORM\Column(type: 'float')]
	#[Groups(["read:Product", "read:Order", "write:Product"])]
    private $price;

    #[ORM\ManyToMany(targetEntity: ProductOption::class, inversedBy: 'products')]
	#[Groups(["read:Product", "write:Product"])]
    private $options;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: OrderDetail::class)]
    private $orderDetails;

    #[ORM\Column(type: 'string', length: 255)]
	#[Groups(["read:Product", "read:Order", "write:Product"])]
    private $picture;

    public function __construct()
    {
        $this->options = new ArrayCollection();
        $this->orderDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, ProductOption>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(ProductOption $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
        }

        return $this;
    }

    public function removeOption(ProductOption $option): self
    {
        $this->options->removeElement($option);

        return $this;
    }

    /**
     * @return Collection<int, OrderDetail>
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetail $orderDetail): self
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails[] = $orderDetail;
            $orderDetail->setProduct($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetail $orderDetail): self
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getProduct() === $this) {
                $orderDetail->setProduct(null);
            }
        }

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }
}
