<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Subject;

class PersonEndpoints
{
    protected string|null $proxyUrl = null;
    protected string|null $oauthAuthorizationEndpoint = null;
    protected string|null $oauthTokenEndpoint = null;
    protected string|null $provideClientKey = null;
    protected string|null $signClientKey = null;
    protected string|null $sharedInbox = null;

    public function getProxyUrl(): ?string
    {
        return $this->proxyUrl;
    }

    public function setProxyUrl(?string $proxyUrl): PersonEndpoints
    {
        $this->proxyUrl = $proxyUrl;

        return $this;
    }

    public function getOauthAuthorizationEndpoint(): ?string
    {
        return $this->oauthAuthorizationEndpoint;
    }

    public function setOauthAuthorizationEndpoint(?string $oauthAuthorizationEndpoint): PersonEndpoints
    {
        $this->oauthAuthorizationEndpoint = $oauthAuthorizationEndpoint;

        return $this;
    }

    public function getOauthTokenEndpoint(): ?string
    {
        return $this->oauthTokenEndpoint;
    }

    public function setOauthTokenEndpoint(?string $oauthTokenEndpoint): PersonEndpoints
    {
        $this->oauthTokenEndpoint = $oauthTokenEndpoint;

        return $this;
    }

    public function getProvideClientKey(): ?string
    {
        return $this->provideClientKey;
    }

    public function setProvideClientKey(?string $provideClientKey): PersonEndpoints
    {
        $this->provideClientKey = $provideClientKey;

        return $this;
    }

    public function getSignClientKey(): ?string
    {
        return $this->signClientKey;
    }

    public function setSignClientKey(?string $signClientKey): PersonEndpoints
    {
        $this->signClientKey = $signClientKey;

        return $this;
    }

    public function getSharedInbox(): ?string
    {
        return $this->sharedInbox;
    }

    public function setSharedInbox(?string $sharedInbox): PersonEndpoints
    {
        $this->sharedInbox = $sharedInbox;

        return $this;
    }
}
