<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFinger;

use Netborg\Fediverse\Api\WebFinger\Model\WebFingerResult;

interface WebFingerResultBuilderInterface
{
    public function setSubject(string $subject): self;

    public function addAlias(string $alias): self;

    public function addProperty(string $name, ?string $value = null): self;

    /**
     * @param array<string,string|null>|null $titles
     * @param array<string,string|null>|null $properties
     */
    public function addLink(
        string $rel,
        string $href = null,
        string $type = null,
        array $titles = [],
        array $properties = []
    ): self;

    public function build(): WebFingerResult;
}
