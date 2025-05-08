<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 128)]
    private string $titre;

    #[ORM\Column(type: Types::STRING, length: 128)]
    private string $slug;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: Restaurant::class, inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurant $restaurant = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    // … getters et setters …

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $t): self
    {
        $this->titre = $t;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $s): self
    {
        $this->slug = $s;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $dt): self
    {
        $this->createdAt = $dt;
        return $this;
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

    public function setRestaurant(?Restaurant $r): self
    {
        $this->restaurant = $r;
        return $this;
    }
}
