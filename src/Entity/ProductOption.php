<?php

namespace App\Entity;

use App\Repository\ProductOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductOptionRepository::class)]
class ProductOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
	#[Groups(["read:Product"])]
    private $directive;

    #[ORM\Column(type: 'integer')]
	#[Groups(["read:Product"])]
    private $allowedChoices;

    #[ORM\Column(type: 'boolean')]
	#[Groups(["read:Product"])]
    private $isRequired;

    #[ORM\ManyToMany(targetEntity: OptionChoice::class, inversedBy: 'options')]
	#[Groups(["read:Product"])]
    private $choices;

    #[ORM\ManyToMany(targetEntity: Product::class, mappedBy: 'options')]
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->choices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDirective(): ?string
    {
        return $this->directive;
    }

    public function setDirective(string $directive): self
    {
        $this->directive = $directive;

        return $this;
    }

    public function getAllowedChoices(): ?int
    {
        return $this->allowedChoices;
    }

    public function setAllowedChoices(int $allowedChoices): self
    {
        $this->allowedChoices = $allowedChoices;

        return $this;
    }

    public function getIsRequired(): ?bool
    {
        return $this->isRequired;
    }

    public function setIsRequired(bool $isRequired): self
    {
        $this->isRequired = $isRequired;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addOption($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeOption($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, OptionChoice>
     */
    public function getChoices(): Collection
    {
        return $this->choices;
    }

    public function addChoice(OptionChoice $choice): self
    {
        if (!$this->choices->contains($choice)) {
            $this->choices[] = $choice;
        }

        return $this;
    }

    public function removeChoice(OptionChoice $choice): self
    {
        $this->choices->removeElement($choice);

        return $this;
    }
}
