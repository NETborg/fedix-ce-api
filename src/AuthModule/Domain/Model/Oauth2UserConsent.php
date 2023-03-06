<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Domain\Model;

use Netborg\Fediverse\Api\UserModule\Domain\Model\User;

class Oauth2UserConsent
{
    private ?Client $client = null;
    private ?User $user = null;
    private ?string $createdAt = null;
    private ?string $expiresAt = null;
    private array $scopes = [];

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): self
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

    /**
     * @return string[]
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * @param string[] $scopes
     */
    public function setScopes(array $scopes): Oauth2UserConsent
    {
        $this->scopes = $scopes;

        return $this;
    }

    public function addScope(string $scope): self
    {
        if (!in_array($scope, $this->scopes)) {
            $this->scopes[] = $scope;
        }

        return $this;
    }

    public function hasScope(string $scope): bool
    {
        return in_array($scope, $this->scopes);
    }

    public function removeScope(string $scope): self
    {
        $this->scopes = array_filter($this->scopes, static fn(string $entry) => $entry !== $scope);

        return $this;
    }
}
