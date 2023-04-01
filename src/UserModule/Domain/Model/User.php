<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Domain\Model;

use Symfony\Component\Serializer\Annotation\Groups;

class User
{
    #[Groups(['save'])]
    private ?int $id = null;
    #[Groups(['save', 'registration', 'get'])]
    private ?string $uuid = null;
    #[Groups(['save', 'registration', 'user.profile'])]
    private ?string $firstName = null;
    #[Groups(['save', 'registration', 'user.profile'])]
    private ?string $lastName = null;
    #[Groups(['save', 'registration', 'user.profile'])]
    private ?string $name = null;
    #[Groups(['save', 'registration', 'user.email'])]
    private ?string $email = null;
    #[Groups(['create', 'sensitive'])]
    private ?string $password = null;
    #[Groups(['save', 'registration', 'get', 'user.profile'])]
    private ?string $username = null;
    #[Groups(['save'])]
    private ?bool $active = null;
    #[Groups(['save', 'user.profile'])]
    private ?string $publicKey = null;
    #[Groups(['save', 'registration'])]
    private ?string $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

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

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
