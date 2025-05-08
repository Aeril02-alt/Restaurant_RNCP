<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: RestaurantRepository::class)]
class Restaurant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 36, unique: true)]
    private string $uuid;

    #[ORM\Column(type: Types::STRING, length: 32)]
    private string $name;

    #[ORM\Column(type: Types::TEXT)]
    private string $description;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $amOpeningTime = [];

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $pmOpeningTime = [];

    #[ORM\Column(type: Types::SMALLINT)]
    private int $maxGuest;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToOne(inversedBy: 'restaurant', targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $owner = null;

    #[ORM\OneToMany(mappedBy: 'restaurant', targetEntity: Picture::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $pictures;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->uuid      = Uuid::uuid4()->toString();
        $this->pictures  = new ArrayCollection();
    }

    // … getters et setters …

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $desc): self
    {
        $this->description = $desc;
        return $this;
    }

    public function getAmOpeningTime(): array
    {
        return $this->amOpeningTime;
    }

    public function setAmOpeningTime(array $times): self
    {
        $this->amOpeningTime = $times;
        return $this;
    }

    public function getPmOpeningTime(): array
    {
        return $this->pmOpeningTime;
    }

    public function setPmOpeningTime(array $times): self
    {
        $this->pmOpeningTime = $times;
        return $this;
    }

    public function getMaxGuest(): int
    {
        return $this->maxGuest;
    }

    public function setMaxGuest(int $n): self
    {
        $this->maxGuest = $n;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setUpdatedAt(\DateTimeInterface $dt): self
    {
        $this->updatedAt = $dt;
        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $u): self
    {
        $this->owner = $u;
        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $pic): self
    {
        if (!$this->pictures->contains($pic)) {
            $this->pictures->add($pic);
            $pic->setRestaurant($this);
        }
        return $this;
    }

    public function removePicture(Picture $pic): self
    {
        if ($this->pictures->removeElement($pic)) {
            $pic->setRestaurant(null);
        }
        return $this;
    }
}
