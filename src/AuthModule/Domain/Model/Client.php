<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Domain\Model;

class Client
{
    private ?string $identifier = null;
    private ?string $name = null;
    private ?string $secret = null;
    private bool $active = true;
    private bool $confidential = false;
    private bool $allowPlainTextPkce = false;
    /** @var string[] */
    private array $redirectUris = [];
    /** @var string[] */
    private array $grants = [];
    /** @var string[] */
    private array $scopes = [];

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSecret(): ?string
    {
        return $this->secret;
    }

    public function setSecret(string $secret): self
    {
        $this->secret = $secret;

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

    public function isConfidential(): bool
    {
        return $this->confidential;
    }

    public function setConfidential(bool $confidential): self
    {
        $this->confidential = $confidential;

        return $this;
    }

    public function isAllowPlainTextPkce(): bool
    {
        return $this->allowPlainTextPkce;
    }

    public function setAllowPlainTextPkce(bool $allowPlainTextPkce): self
    {
        $this->allowPlainTextPkce = $allowPlainTextPkce;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getRedirectUris(): array
    {
        return $this->redirectUris;
    }

    /**
     * @param string[] $redirectUris
     */
    public function setRedirectUris(array $redirectUris): self
    {
        $this->redirectUris = $redirectUris;

        return $this;
    }

    public function addRedirectUri(string $redirectUri): self
    {
        if (!in_array($redirectUri, $this->redirectUris)) {
            $this->redirectUris[] = $redirectUri;
        }

        return $this;
    }

    public function removeRedirectUri(string $redirectUri): self
    {
        $this->redirectUris = array_filter($this->redirectUris, static fn (string $entry) => $entry !== $redirectUri);

        return $this;
    }

    /**
     * @return string[]
     */
    public function getGrants(): array
    {
        return $this->grants;
    }

    /**
     * @param string[] $grants
     */
    public function setGrants(array $grants): self
    {
        $this->grants = $grants;

        return $this;
    }

    public function addGrant(string $grant): self
    {
        if (!in_array($grant, $this->grants)) {
            $this->grants[] = $grant;
        }

        return $this;
    }

    public function removeGrant(string $grant): self
    {
        $this->grants = array_filter($this->grants, static fn (string $entry) => $entry !== $grant);

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
    public function setScopes(array $scopes): self
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

    public function removeScope(string $scope): self
    {
        $this->scopes = array_filter($this->scopes, static fn (string $entry) => $entry !== $scope);

        return $this;
    }
}
