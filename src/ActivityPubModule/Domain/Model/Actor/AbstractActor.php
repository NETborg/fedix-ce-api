<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Collection;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\ObjectType;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\OrderedCollection;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Subject\PublicKey;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractActor extends ObjectType
{
    public const TYPE = 'Actor';

    protected static string $type = self::TYPE;

    protected array $schemaContext = [
        'https://w3id.org/security/v1',
    ];

    #[Groups(['create', 'get', 'update'])]
    #[Assert\NotBlank(groups: ['create', 'update'])]
    protected string|null $preferredUsername = null;
    #[Groups(['get'])]
    protected string|OrderedCollection|null $inbox = null;
    #[Groups(['get'])]
    protected string|OrderedCollection|null $outbox = null;

    #[Groups(['get'])]
    protected string|Collection|null $following = null;
    #[Groups(['get'])]
    protected string|Collection|null $followers = null;
    #[Groups(['get'])]
    protected string|Collection|null $liked = null;
    #[Groups(['get'])]
    protected string|Collection|null $streams = null;

    #[Groups(['get'])]
    protected PublicKey|null $publicKey = null;

    /** @var string[] */
    protected array $owners = [];

    public function getPreferredUsername(): ?string
    {
        return $this->preferredUsername;
    }

    public function setPreferredUsername(?string $preferredUsername): self
    {
        $this->preferredUsername = $preferredUsername;

        return $this;
    }

    public function getInbox(): string|OrderedCollection|null
    {
        return $this->inbox;
    }

    public function setInbox(string|OrderedCollection|null $inbox): self
    {
        $this->inbox = $inbox;

        return $this;
    }

    public function getOutbox(): string|OrderedCollection|null
    {
        return $this->outbox;
    }

    public function setOutbox(string|OrderedCollection|null $outbox): self
    {
        $this->outbox = $outbox;

        return $this;
    }

    public function getFollowing(): Collection|string|null
    {
        return $this->following;
    }

    public function setFollowing(Collection|string|null $following): self
    {
        $this->following = $following;

        return $this;
    }

    public function getFollowers(): Collection|string|null
    {
        return $this->followers;
    }

    public function setFollowers(Collection|string|null $followers): self
    {
        $this->followers = $followers;

        return $this;
    }

    public function getLiked(): Collection|string|null
    {
        return $this->liked;
    }

    public function setLiked(Collection|string|null $liked): self
    {
        $this->liked = $liked;

        return $this;
    }

    public function getStreams(): Collection|string|null
    {
        return $this->streams;
    }

    public function setStreams(Collection|string|null $streams): self
    {
        $this->streams = $streams;

        return $this;
    }

    public function getPublicKey(): ?PublicKey
    {
        return $this->publicKey;
    }

    public function setPublicKey(?PublicKey $publicKey): self
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    public function setOwners(array $owners): self
    {
        $this->owners = $owners;

        return $this;
    }

    public function getOwners(): array
    {
        return $this->owners;
    }

    public function addOwner(string $owner): self
    {
        if (!in_array($owner, $this->owners)) {
            $this->owners[] = $owner;
        }

        return $this;
    }

    public function addOwners(array $owners): self
    {
        $this->owners = array_unique(array_merge($this->owners, $owners));

        return $this;
    }

    public function removeOwner(string $owner): self
    {
        $this->owners = array_filter($this->owners, static fn(string $ownerId) => $ownerId !== $owner);

        return $this;
    }

    public function removeOwners(array $owners): self
    {
        $this->owners = array_filter($this->owners, static fn(string $ownerId) => !in_array($ownerId, $owners));

        return $this;
    }

    public function hasOwner(string $owner): bool
    {
        return in_array($owner, $this->owners);
    }
}
