<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\NewsRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
#[ApiResource(
    denormalizationContext: ['groups' => ['news:write']],
    normalizationContext: ['groups' => ['news:read']],
    paginationItemsPerPage: 10,
)]
#[ApiFilter(BooleanFilter::class, properties: ['isPublished', 'category'])]
class News
{

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->isPublished = false;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 180)]
    #[Groups(['news:read', 'news:write'])]
    private ?string $title;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    #[Groups(['news:read', 'news:write'])]
    private ?string $subtitle;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    #[Groups(['news:read', 'news:write'])]
    private ?string $description;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['news:read', 'news:write'])]
    private ?string $image;

    #[ORM\Column]
    #[Assert\Type('bool')]
    #[Groups(['news:read', 'news:write'])]
    private ?bool $isPublished;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['news:read', 'news:write'])]
    private ?Category $category;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['news:read', 'news:write'])]
    private ?string $video;

    #[ORM\Column(nullable: true)]
    #[Groups(['news:read'])]
    private DateTimeImmutable $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): static
    {
        $this->subtitle = $subtitle;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(?string $video): static
    {
        $this->video = $video;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
