<?php

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Repository\ActorRepository;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ActorRepository::class)]
#[UniqueEntity(fields: ['preferredUsername'])]
#[ORM\HasLifecycleCallbacks]
class Actor
{
    public const PERSON = 'Person';
    public const ORGANIZATION = 'Organization';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['Created'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: [self::PERSON, self::ORGANIZATION])]
    #[Groups(['Actor', 'Actors'])]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['Actor', 'Actors'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['Actor'])]
    private ?array $nameMap = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['Actor'])]
    private ?string $summary = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['Actor'])]
    private ?array $summaryMap = null;

    #[ORM\Column(length: 255)]
    #[Groups(['Actor', 'Actors'])]
    private ?string $preferredUsername = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['Actor'])]
    private ?string $publicKey = null;

    #[ORM\Column]
    #[Groups(['Actor'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['Actor'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'actors')]
    #[Groups(['Users'])]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getNameMap(): array
    {
        return $this->nameMap;
    }

    public function setNameMap(?array $nameMap): self
    {
        $this->nameMap = $nameMap;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getSummaryMap(): array
    {
        return $this->summaryMap;
    }

    public function setSummaryMap(?array $summaryMap): self
    {
        $this->summaryMap = $summaryMap;

        return $this;
    }

    public function getPreferredUsername(): ?string
    {
        return $this->preferredUsername;
    }

    public function setPreferredUsername(string $preferredUsername): self
    {
        $this->preferredUsername = $preferredUsername;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addActor($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeActor($this);
        }

        return $this;
    }

    #[ORM\PrePersist]
    public function onCreate(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
