<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Domain\Model;

class WebFingerProperties
{
    /** @var array<string,string|null> */
    private array $properties = [];

    public function __set(string $name, ?string $value): void
    {
        $this->properties[$name] = $value;
    }

    public function __get(string $name): ?string
    {
        return $this->properties[$name] ?? null;
    }

    public function setProperty(string $name, ?string $value): self
    {
        $this->$name = $value;

        return $this;
    }

    public function getProperty(string $name): ?string
    {
        return $this->$name;
    }

    public function addProperty(string $name, ?string $value): self
    {
        return $this->setProperty($name, $value);
    }

    /** @param array<string,string|null> $properties */
    public function setProperties(array $properties): self
    {
        $this->properties = $properties;

        return $this;
    }

    /** @return array<string,string|null> */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function removeProperty(string $name): self
    {
        unset($this->properties[$name]);

        return $this;
    }

    public function clearProperties(): self
    {
        $this->properties = [];

        return $this;
    }
}
