<?php

namespace App\Entity;

use App\Repository\OptionChoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OptionChoiceRepository::class)]
class OptionChoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
	#[Groups(["read:Product"])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
	#[Groups(["read:Product", "read:Order", "read:ProductOption"])]
    private $name;

    #[ORM\Column(type: 'float')]
	#[Groups(["read:Product", "read:Order", "read:ProductOption"])]
    private $extraPrice;

    #[ORM\Column(type: 'boolean')]
	#[Groups(["read:Product", "read:ProductOption"])]
    private $isMultiple;

    #[ORM\ManyToMany(targetEntity: ProductOption::class, mappedBy: 'choices')]
    private $options;

    #[ORM\ManyToMany(targetEntity: OrderDetail::class, mappedBy: 'optionChoices')]
    private $orderDetails;

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

    public function getExtraPrice(): ?float
    {
        return $this->extraPrice;
    }

    public function setExtraPrice(float $extraPrice): self
    {
        $this->extraPrice = $extraPrice;

        return $this;
    }

    public function getIsMultiple(): ?bool
    {
        return $this->isMultiple;
    }

    public function setIsMultiple(bool $isMultiple): self
    {
        $this->isMultiple = $isMultiple;

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
            $option->addChoice($this);
        }

        return $this;
    }

    public function removeOption(ProductOption $option): self
    {
        if ($this->options->removeElement($option)) {
            $option->removeChoice($this);
        }

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
            $orderDetail->addOptionChoice($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetail $orderDetail): self
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            $orderDetail->removeOptionChoice($this);
        }

        return $this;
    }
}
