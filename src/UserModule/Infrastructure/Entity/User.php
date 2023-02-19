<?php

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity\Actor;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`', )]
#[UniqueEntity('uuid', groups: ['Create'])]
#[UniqueEntity('email', groups: ['Create'])]
#[UniqueEntity('username', groups: ['Create'])]
#[ORM\HasLifecycleCallbacks]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID, unique: true, nullable: false)]
    #[Assert\NotBlank(groups: ['Update'])]
    #[Assert\Uuid(groups: ['Update'])]
    #[Groups(['Create', 'User', 'Users'])]
    private ?string $uuid = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(groups: ['Create', 'Update'])]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_\-\.]+$/', groups: ['Create', 'Update'])]
    #[Groups(['Create', 'User', 'Users'])]
    private ?string $username = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(groups: ['Create', 'Update'])]
    #[Assert\Email(groups: ['Create', 'Update'])]
    #[Groups(['Create', 'User', 'Users'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['Create', 'ResetPassword'])]
    #[Assert\Length(min: 8, groups: ['Create', 'ResetPassword'])]
    #[Groups(['Create'])]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['Create', 'User'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['Create', 'User'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['User', 'Users'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['Create', 'User'])]
    private ?string $publicKey = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    #[Groups(['Create', 'Update', 'User'])]
    private bool $active = false;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['User'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Groups(['User'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Actor::class, inversedBy: 'users', cascade: ['persist', 'merge'])]
    #[Groups(['Actors'])]
    private Collection $actors;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ActivationLink::class, orphanRemoval: true)]
    private Collection $activationLinks;

    public function __construct()
    {
        $this->actors = new ArrayCollection();
        $this->activationLinks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(?string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPublicKey(): ?string
    {
        return $this->publicKey;
    }

    public function setPublicKey(?string $publicKey): self
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection<int, Actor>
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Actor $actor): self
    {
        if (!$this->actors->contains($actor)) {
            $this->actors->add($actor);
        }

        return $this;
    }

    public function removeActor(Actor $actor): self
    {
        $this->actors->removeElement($actor);

        return $this;
    }

    /**
     * @return Collection<int, Actor>
     */
    public function getActivationLinks(): Collection
    {
        return $this->activationLinks;
    }

    public function addActivationLink(ActivationLink $activationLink): self
    {
        if (!$this->activationLinks->contains($activationLink)) {
            $this->activationLinks->add($activationLink);
        }

        return $this;
    }

    public function removeActivationLink(ActivationLink $activationLink): self
    {
        $this->activationLinks->removeElement($activationLink);

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->uuid = Uuid::v7()->toRfc4122();
        $this->createdAt = new \DateTimeImmutable();

        if ($this->firstName || $this->lastName) {
            $this->name = sprintf('%s %s', $this->firstName, $this->lastName);
        }
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
