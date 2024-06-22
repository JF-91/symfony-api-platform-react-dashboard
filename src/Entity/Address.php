<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\Table(name: 'addresses')]
#[ApiResource(
    denormalizationContext: ['groups' => ['address:write']],
    normalizationContext: ['groups' => ['address:read']],
    paginationItemsPerPage: 10,
)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 180)]
    #[Groups(['address:read', 'address:write'])]
    private ?string $addressName;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 50)]
    #[Groups(['address:read', 'address:write'])]
    private ?string $city;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 10)]
    #[Groups(['address:read', 'address:write'])]
    private ?string $zip;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 50)]
    #[Groups(['address:read', 'address:write'])]
    private ?string $country;

    #[ORM\OneToOne(inversedBy: 'address', cascade: ['persist', 'remove'])]
    #[Groups(['address:read', 'address:write'])]
    private User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddressName(): ?string
    {
        return $this->addressName;
    }

    public function setAddressName(string $addressName): static
    {
        $this->addressName = $addressName;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): static
    {
        $this->zip = $zip;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    #[Groups(['address:read'])]
    public function getFullAddress(): string
    {
        return implode('<br>', array_filter([
            htmlspecialchars($this->addressName, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($this->city, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($this->zip, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($this->country, ENT_QUOTES, 'UTF-8'),
        ]));
    }
}
