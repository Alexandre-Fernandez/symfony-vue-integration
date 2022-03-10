<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
#[ApiResource(
	normalizationContext: ["groups" => ["read:Order"]]
)]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
	#[Groups(["read:Order"])]
    private $id;

    #[ORM\Column(type: 'datetime_immutable')]
	#[Groups(["read:Order"])]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
	#[Groups(["read:Order"])]
    private $deliveredAt;

    #[ORM\OneToMany(mappedBy: 'orderObj', targetEntity: OrderDetail::class)]
	#[Groups(["read:Order"])]
    private $details;

    #[ORM\ManyToOne(targetEntity: UserAddress::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
	#[Groups(["read:Order"])]
    private $address;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
	#[Groups(["read:Order"])]
    private $user;

    public function __construct()
    {
		$this->createdAt = new \DateTimeImmutable();
        $this->details = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDeliveredAt(): ?\DateTimeImmutable
    {
        return $this->deliveredAt;
    }

    public function setDeliveredAt(?\DateTimeImmutable $deliveredAt): self
    {
        $this->deliveredAt = $deliveredAt;

        return $this;
    }

    /**
     * @return Collection<int, OrderDetail>
     */
    public function getDetails(): Collection
    {
        return $this->details;
    }

    public function addDetail(OrderDetail $detail): self
    {
        if (!$this->details->contains($detail)) {
            $this->details[] = $detail;
            $detail->setOrderObj($this);
        }

        return $this;
    }

    public function removeDetail(OrderDetail $detail): self
    {
        if ($this->details->removeElement($detail)) {
            // set the owning side to null (unless already changed)
            if ($detail->getOrderObj() === $this) {
                $detail->setOrderObj(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?UserAddress
    {
        return $this->address;
    }

    public function setAddress(?UserAddress $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
