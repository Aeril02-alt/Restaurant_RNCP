<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 36, unique: true)]
    private string $uuid;

    #[ORM\Column(type: Types::STRING, length: 32)]
    private string $firstName;

    #[ORM\Column(type: Types::STRING, length: 64)]
    private string $lastName;

    #[ORM\Column(type: Types::STRING, length: 64, unique: true)]
    private string $email;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $password;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $guestNumber = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $allergy = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToOne(mappedBy: 'owner', targetEntity: Restaurant::class, cascade: ['persist', 'remove'])]
    private ?Restaurant $restaurant = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->uuid      = Uuid::uuid4()->toString();
    }

    // … getters et setters pour chaque propriété …

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    // idem pour lastName, email, password, roles, guestNumber, allergy...

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $dt): self
    {
        $this->updatedAt = $dt;
        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;
        return $this;
    }
}