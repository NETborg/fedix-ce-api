<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Actor;

use Netborg\Fediverse\Api\Model\ActivityPub\Collection;
use Netborg\Fediverse\Api\Model\ActivityPub\ObjectType;
use Netborg\Fediverse\Api\Model\ActivityPub\OrderedCollection;
use Netborg\Fediverse\Api\Model\ActivityPub\Subject\PublicKey;

class Person extends ObjectType
{
    public const TYPE = 'Person';

    protected static string $type = self::TYPE;

    protected array $schemaContext = [
        'https://w3id.org/security/v1'
    ];

    protected string|null $preferredUsername = null;
    protected string|OrderedCollection|null $inbox = null;
    protected string|OrderedCollection|null $outbox = null;

    protected string|Collection|null $following = null;
    protected string|Collection|null $followers = null;
    protected string|Collection|null $liked = null;
    protected string|Collection|null $streams = null;

    protected PublicKey|null $publicKey = null;

    public function getPreferredUsername(): ?string
    {
        return $this->preferredUsername;
    }

    public function setPreferredUsername(?string $preferredUsername): Person
    {
        $this->preferredUsername = $preferredUsername;

        return $this;
    }

    public function getInbox(): string|OrderedCollection|null
    {
        return $this->inbox;
    }

    public function setInbox(string|OrderedCollection|null $inbox): Person
    {
        $this->inbox = $inbox;

        return $this;
    }

    public function getOutbox(): string|OrderedCollection|null
    {
        return $this->outbox;
    }

    public function setOutbox(string|OrderedCollection|null $outbox): Person
    {
        $this->outbox = $outbox;

        return $this;
    }

    public function getFollowing(): Collection|string|null
    {
        return $this->following;
    }

    public function setFollowing(Collection|string|null $following): Person
    {
        $this->following = $following;

        return $this;
    }

    public function getFollowers(): Collection|string|null
    {
        return $this->followers;
    }

    public function setFollowers(Collection|string|null $followers): Person
    {
        $this->followers = $followers;

        return $this;
    }

    public function getLiked(): Collection|string|null
    {
        return $this->liked;
    }

    public function setLiked(Collection|string|null $liked): Person
    {
        $this->liked = $liked;

        return $this;
    }

    public function getStreams(): Collection|string|null
    {
        return $this->streams;
    }

    public function setStreams(Collection|string|null $streams): Person
    {
        $this->streams = $streams;

        return $this;
    }

    public function getPublicKey(): ?PublicKey
    {
        return $this->publicKey;
    }

    public function setPublicKey(?PublicKey $publicKey): Person
    {
        $this->publicKey = $publicKey;

        return $this;
    }
}
