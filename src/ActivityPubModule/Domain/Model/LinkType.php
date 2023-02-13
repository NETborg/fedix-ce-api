<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model;

use Symfony\Component\Validator\Constraints as Assert;

abstract class LinkType
{
    protected static string $type = 'LinkType';

    #[
        Assert\Uuid(groups: ['Update']),
        Assert\NotBlank(groups: ['Update'])
    ]
    private string $id;
    private string|null $name = null;
    #[
        Assert\Url(groups: ['Default', 'Create', 'Update'])
    ]
    private string|null $href = null;

    #[
        Assert\Regex(
            pattern: '/^[a-z]{2}(_[a-zA-Z0-1]{4}){0,1}(_[A-Z]{2}){0,1}$/',
            groups: ['Default', 'Create', 'Update']
        )
    ]
    private string|null $hreflang = null;
    private string|array|null $rel = null;

    #[
        Assert\Regex(
            pattern: '/^(text|application|audio|video|image|message|model|multipart)\/[a-z0-9+\-\.]+$/',
            groups: ['Default', 'Create', 'Update']
        )
    ]
    private string|null $mediaType = null;
    private int|null $height = null;
    private int|null $width = null;
    private string|LinkType|ObjectType|null $preview = null;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): LinkType
    {
        $this->id = $id;

        return $this;
    }

    public function getType(): string
    {
        return static::$type;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): LinkType
    {
        $this->name = $name;

        return $this;
    }

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function setHref(?string $href): LinkType
    {
        $this->href = $href;

        return $this;
    }

    public function getHreflang(): ?string
    {
        return $this->hreflang;
    }

    public function setHreflang(?string $hreflang): LinkType
    {
        $this->hreflang = $hreflang;

        return $this;
    }

    public function getRel(): array|string|null
    {
        return $this->rel;
    }

    public function setRel(array|string|null $rel): LinkType
    {
        $this->rel = $rel;

        return $this;
    }

    public function getMediaType(): string
    {
        return $this->mediaType;
    }

    public function setMediaType(string $mediaType): LinkType
    {
        $this->mediaType = $mediaType;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): LinkType
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): LinkType
    {
        $this->width = $width;

        return $this;
    }

    public function getPreview(): LinkType|string|ObjectType|null
    {
        return $this->preview;
    }

    public function setPreview(LinkType|string|ObjectType|null $preview): LinkType
    {
        $this->preview = $preview;

        return $this;
    }
}
