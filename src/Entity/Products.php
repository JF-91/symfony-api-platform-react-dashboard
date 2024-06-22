<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProductsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
#[ApiResource(
    denormalizationContext: ['groups' => ['products:write']],
    normalizationContext: ['groups' => ['products:read']],
    paginationItemsPerPage: 10,

)]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 180)]
    #[Groups(['products:read', 'products:write'])]
    private ?string $name;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['products:read', 'products:write'])]
    private ?float $price;

    #[ORM\Column]
    #[Groups(['products:read', 'products:write'])]
    private ?bool $isPublished;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[Groups(['products:read', 'products:write'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['products:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['products:read', 'products:write'])]
    private Category $category;

    #[ORM\OneToOne(mappedBy: 'product')]
    #[Groups(['products:read', 'products:write'])]
    private Promotions $promotions;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getPromotions(): ?Promotions
    {
        return $this->promotions;
    }

    public function setPromotions(?Promotions $promotions): static
    {
        // unset the owning side of the relation if necessary
        if ($promotions === null && $this->promotions !== null) {
            $this->promotions->setProduct(null);
        }

        // set the owning side of the relation if necessary
        if ($promotions !== null && $promotions->getProduct() !== $this) {
            $promotions->setProduct($this);
        }

        $this->promotions = $promotions;

        return $this;
    }
}
