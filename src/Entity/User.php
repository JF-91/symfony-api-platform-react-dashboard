<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\Table(name: 'users')]
#[ApiResource(
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
    paginationItemsPerPage: 10,
)]
#[ApiFilter(SearchFilter::class, properties: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    public function __construct() {
        $this->createdAt = new DateTimeImmutable();
        $this->roles = ['ROLE_USER'];
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['user:read', 'user:write'])]
    private ?string $email;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['user:read', 'user:write'])]
    private ?string $password;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['user:read', 'user:write'])]
    private ?string $firstName ;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['user:read', 'user:write'])]
    private ?string $lastName;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Address $address;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
       
        return $this->roles;
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }


    #[Groups(['user:read'])]
    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    #[Groups(['user:read'])]
    public function getFullUserData(): string
    {
        if ($this->address === null) {
            return 'Address not available';
        }
    
        return implode('<br>', array_filter([
            htmlspecialchars($this->firstName . ' ' . $this->lastName, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($this->email, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($this->address->getAddressName(), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($this->address->getCity(), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($this->address->getZip(), ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($this->address->getCountry(), ENT_QUOTES, 'UTF-8'),
        ]));
    }

    public function setAddress(?Address $address): static
    {
        // unset the owning side of the relation if necessary
        if ($address === null && $this->address !== null) {
            $this->address->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($address !== null && $address->getUser() !== $this) {
            $address->setUser($this);
        }

        $this->address = $address;

        return $this;
    }
}
