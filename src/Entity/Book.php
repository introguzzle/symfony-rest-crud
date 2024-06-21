<?php

namespace App\Entity;

use App\Repository\BookRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping;
use DateTimeInterface;

#[Mapping\Table(name: 'books')]
#[Mapping\Entity(repositoryClass: BookRepository::class)]
class Book extends Entity
{
    #[Mapping\Id]
    #[Mapping\GeneratedValue(strategy: 'SEQUENCE')]
    #[Mapping\Column(type: Types::INTEGER)]
    private int $id;

    #[Mapping\Column(type: Types::STRING, length: 255, unique: true)]
    private string $title;

    #[Mapping\Column(type: Types::STRING, length: 255)]
    private string $author;

    #[Mapping\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $publishedAt;

    #[Mapping\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $createdAt;

    #[Mapping\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $updatedAt;


    #[Mapping\ManyToOne(targetEntity: User::class, cascade: ['all'], inversedBy: 'books')]
    #[Mapping\JoinColumn(nullable: false, onDelete: 'cascade')]
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;
        return $this;
    }

    public function getPublishedAt(): ?DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;
        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getHiddenProperties(): array
    {
        return [];
    }
}
