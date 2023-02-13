<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Subject;

use Symfony\Component\Validator\Constraints as Assert;

class PublicKey
{
    #[Assert\All([
        new Assert\NotBlank(groups: ['Default', 'Create', 'Update']),
        new Assert\Url(groups: ['Default', 'Create', 'Update']),
    ])]
    protected string|null $id = null;

    #[Assert\All([
        new Assert\NotBlank(groups: ['Default', 'Create', 'Update']),
        new Assert\Url(groups: ['Default', 'Create', 'Update']),
    ])]
    protected string|null $owner = null;

    #[Assert\NotBlank(groups: ['Default', 'Create', 'Update'])]
    protected string|null $publicKeyPem = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): PublicKey
    {
        $this->id = $id;

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(?string $owner): PublicKey
    {
        $this->owner = $owner;

        return $this;
    }

    public function getPublicKeyPem(): ?string
    {
        return $this->publicKeyPem;
    }

    public function setPublicKeyPem(?string $publicKeyPem): PublicKey
    {
        $this->publicKeyPem = $publicKeyPem;

        return $this;
    }
}
