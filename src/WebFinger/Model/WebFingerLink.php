<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFinger\Model;

use Symfony\Component\Validator\Constraints as Assert;

class WebFingerLink
{
    #[Assert\NotNull]
    protected ?string $rel = null;
    protected ?string $href = null;
    protected ?string $type = null;
    protected ?WebFingerTitles $titles = null;
    protected ?WebFingerProperties $properties = null;

    public function getRel(): ?string
    {
        return $this->rel;
    }

    public function setRel(?string $rel): self
    {
        $this->rel = $rel;

        return $this;
    }

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function setHref(?string $href): self
    {
        $this->href = $href;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTitles(): ?WebFingerTitles
    {
        return $this->titles;
    }

    public function setTitles(?WebFingerTitles $titles): self
    {
        $this->titles = $titles;

        return $this;
    }

    public function getProperties(): ?WebFingerProperties
    {
        return $this->properties;
    }

    public function setProperties(?WebFingerProperties $properties): self
    {
        $this->properties = $properties;

        return $this;
    }
}
