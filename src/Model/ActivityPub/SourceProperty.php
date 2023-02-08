<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub;

class SourceProperty
{
    protected ?string $content = null;
    protected ?string $mediaType = null;

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getMediaType(): ?string
    {
        return $this->mediaType;
    }

    public function setMediaType(?string $mediaType): self
    {
        $this->mediaType = $mediaType;

        return $this;
    }
}
