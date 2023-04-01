<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Subject;

use Symfony\Component\Validator\Constraints as Assert;

class PublicKey
{
    #[Assert\All([
        new Assert\NotBlank(groups: ['Default', 'create', 'update']),
        new Assert\Url(groups: ['Default', 'create', 'update']),
    ])]
    protected string|null $id = null;

    #[Assert\All([
        new Assert\NotBlank(groups: ['Default', 'create', 'Update']),
        new Assert\Url(groups: ['Default', 'create', 'update']),
    ])]
    protected string|null $owner = null;

    #[Assert\NotBlank(groups: ['Default', 'create', 'update'])]
    protected string|null $publicKeyPem = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(?string $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getPublicKeyPem(): ?string
    {
        return $this->publicKeyPem;
    }

    public function setPublicKeyPem(?string $publicKeyPem): self
    {
        $this->publicKeyPem = $publicKeyPem;

        return $this;
    }
}
