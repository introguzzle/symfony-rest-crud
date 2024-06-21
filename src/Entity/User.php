<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping;

#[Mapping\Entity(repositoryClass: UserRepository::class)]
#[Mapping\Table(name: 'users')]
class User extends Entity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Mapping\Id]
    #[Mapping\GeneratedValue(strategy: 'SEQUENCE')]
    #[Mapping\Column(type: 'integer')]
    private int $id;

    #[Mapping\Column(type: Types::STRING, length: 255, unique: true)]
    private string $name;

    #[Mapping\Column(type: Types::STRING, length: 255, unique: true)]
    private string $email;

    #[Mapping\Column(type: Types::STRING, length: 255)]
    private string $password;

    #[Mapping\Column(type: Types::JSON)]
    private array $roles;

    #[Mapping\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeInterface $createdAt;

    #[Mapping\Column(type: Types::DATETIME_MUTABLE)]
    private DateTimeInterface $updatedAt;

    /**
     * @var Collection<int, Book>
     */
    #[Mapping\OneToMany(targetEntity: Book::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private Collection $books;


    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    /**
     * @param Collection<int, Book> $books
     * @return $this
     */
    public function setBooks(Collection $books): static
    {
        $this->books = $books;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials(): void
    {

    }

    public function getUserIdentifier(): string
    {
        return $this->name;
    }

    public function getHiddenProperties(): array
    {
        return ['password'];
    }
}
