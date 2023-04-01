<?php

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\AbstractActor as DomainActor;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Application as DomainApplication;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Group as DomainGroup;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Organization as DomainOrganization;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person as DomainPerson;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Service as DomainService;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Repository\ActorRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ActorRepository::class)]
#[UniqueEntity(fields: 'uuid', groups: ['create', 'update'])]
#[UniqueEntity(fields: 'preferredUsername', groups: ['create', 'update'])]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discriminator', type: 'string')]
#[ORM\DiscriminatorMap([
    DomainActor::TYPE => Actor::class,
    DomainApplication::TYPE => Application::class,
    DomainGroup::TYPE => Group::class,
    DomainOrganization::TYPE => Organization::class,
    DomainPerson::TYPE => Person::class,
    DomainService::TYPE => Service::class,
])]
#[ORM\HasLifecycleCallbacks]
class Actor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['created'])]
    protected ?int $id = null;

    #[ORM\Column(type: Types::GUID, unique: true, nullable: false)]
    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Groups(['Actor', 'Actors'])]
    protected ?string $uuid = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Assert\Choice(choices: [
        DomainApplication::TYPE,
        DomainGroup::TYPE,
        DomainOrganization::TYPE,
        DomainPerson::TYPE,
        DomainService::TYPE,
    ])]
    #[Groups(['Actor', 'Actors'])]
    protected ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Groups(['Actor', 'Actors'])]
    protected ?string $name = null;

    #[ORM\Column(type: 'json_document', nullable: true, options: ['jsonb' => true])]
    #[Groups(['Actor'])]
    protected ?array $nameMap = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['Actor'])]
    protected ?string $summary = null;

    #[ORM\Column(type: 'json_document', nullable: true, options: ['jsonb' => true])]
    #[Groups(['Actor'])]
    protected ?array $summaryMap = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['Actor'])]
    protected ?string $content = null;

    #[ORM\Column(type: 'json_document', nullable: true, options: ['jsonb' => true])]
    #[Groups(['Actor'])]
    protected ?array $contentMap = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(groups: ['create', 'update'])]
    #[Groups(['Actor', 'Actors'])]
    protected ?string $preferredUsername = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['Actor'])]
    protected ?string $publicKey = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['Actor'])]
    protected ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Groups(['Actor'])]
    protected ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: 'json_document', nullable: true, options: ['jsonb' => true])]
    #[Assert\Count(min: 1, groups: ['create', 'update'])]
    #[Groups(['Users'])]
    protected ?array $users = null;

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

    public function getNameMap(): ?array
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

    public function getSummaryMap(): ?array
    {
        return $this->summaryMap;
    }

    public function setSummaryMap(?array $summaryMap): self
    {
        $this->summaryMap = $summaryMap;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getContentMap(): ?array
    {
        return $this->contentMap;
    }

    public function setContentMap(?array $contentMap): self
    {
        $this->contentMap = $contentMap;

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

    public function getUsers(): ?array
    {
        return $this->users;
    }

    public function setUsers(array $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function addUser(string $userId): self
    {
        if (is_null($this->users)) {
            $this->users = [];
        }

        if (!in_array($userId, $this->users)) {
            $this->users[] = $userId;
        }

        return $this;
    }

    public function removeUser(string $userId): self
    {
        if (in_array($userId, $this->users ?? [])) {
            $this->users = array_filter($this->users, static fn (string $user) => $user !== $userId);
        }

        return $this;
    }

    #[ORM\PrePersist]
    public function onCreate(): void
    {
        $this->uuid ??= Uuid::v7()->toRfc4122();
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
