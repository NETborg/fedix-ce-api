<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFinger\Model;

class WebFingerResult
{
    protected ?string $subject = null;
    /** @var string[]|null */
    protected ?array $aliases = null;
    protected ?WebFingerProperties $properties = null;
    /** @var array<WebFingerLink>|null */
    protected ?array $links = null;

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getAliases(): ?array
    {
        return $this->aliases;
    }

    /**
     * @param string[]|null $aliases
     */
    public function setAliases(?array $aliases): self
    {
        $this->aliases = $aliases;

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

    /**
     * @return array<WebFingerLink>|null
     */
    public function getLinks(): ?array
    {
        return $this->links;
    }

    /**
     * @param array<WebFingerLink>|null $links
     */
    public function setLinks(?array $links): self
    {
        $this->links = $links;

        return $this;
    }
}
