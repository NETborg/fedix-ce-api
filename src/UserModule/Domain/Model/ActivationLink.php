<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Domain\Model;

use Symfony\Component\Serializer\Annotation\Groups;

class ActivationLink
{
    #[Groups(['save'])]
    private ?int $id = null;
    #[Groups(['save'])]
    private ?string $uuid = null;
    #[Groups(['save'])]
    private ?string $createdAt = null;
    #[Groups(['save'])]
    private ?string $expiresAt = null;
    #[Groups(['save'])]
    private ?User $user = null;

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

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getExpiresAt(): ?string
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?string $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
