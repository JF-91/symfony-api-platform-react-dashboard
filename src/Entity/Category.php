<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\EnumProductType;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ProductType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: 'categories')]
#[ApiResource(
    denormalizationContext: ['groups' => ['category:write']],
    normalizationContext: ['groups' => ['category:read']],
    paginationItemsPerPage: 10,
)]
class Category
{
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->productType = EnumProductType::ANOTHER;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 180, enumType: EnumProductType::class)]
    #[Groups(['category:read', 'category:write'])]
    private EnumProductType $productType;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Groups(['category:read', 'category:write'])]
    private string $nameCategory;

    /**
     * @var Collection<int, Products>
     */
    #[ORM\OneToMany(targetEntity: Products::class, mappedBy: 'category')]
    private Collection $products;

    #[ORM\Column(nullable: true)]
    private DateTimeImmutable $createdAt;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameCategory(): string
    {
        return $this->nameCategory;
    }

    public function setNameCategory(string $nameCategory): static
    {
        $this->nameCategory = $nameCategory;

        return $this;
    }

    public function getType(): EnumProductType
    {
        return $this->productType;
    }

    public function setType(EnumProductType $producttype): static
    {
        $this->productType = $producttype;

        return $this;
    }

    /**
     * @return Collection<int, Products>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Products $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Products $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

}
