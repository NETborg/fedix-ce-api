<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\Event;

use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User as UserEntity;

class UserRegisteredEvent
{
    public const NAME = 'user.registered';

    public static function create(UserEntity $user): self
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
        private int $id,
        private string $uuid,
        private string $email,
        private string $username,
        private ?string $firstName = null,
        private ?string $lastName = null,
        private ?string $name = null,
        private ?string $pubKey = null,
        private \DateTimeInterface|string|null $createdAt = null
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
