<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\Event;

use Netborg\Fediverse\Api\UserModule\Domain\Model\User;

class UserRegisteredEvent
{
    public const NAME = 'user.registered';

    public static function create(User $user): self
    {
        return new self(
            $user->getId(),
            $user->getUuid(),
            $user->getEmail(),
            $user->getUsername(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getName(),
            $user->getPublicKey(),
            $user->getCreatedAt()
        );
    }

    public function __construct(
        private readonly ?int $id,
        private readonly string $uuid,
        private readonly string $email,
        private readonly string $username,
        private readonly ?string $firstName = null,
        private readonly ?string $lastName = null,
        private readonly ?string $name = null,
        private readonly ?string $pubKey = null,
        private readonly \DateTimeInterface|string|null $createdAt = null
    ) {
    }

    public function getEventName(): string
    {
        return self::NAME;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPubKey(): ?string
    {
        return $this->pubKey;
    }

    public function getCreatedAt(): \DateTimeInterface|string|null
    {
        return $this->createdAt;
    }
}
