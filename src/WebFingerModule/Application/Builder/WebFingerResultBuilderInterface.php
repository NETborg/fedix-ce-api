<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Application\Builder;

use Netborg\Fediverse\Api\WebFingerModule\Domain\Model\WebFingerResult;

interface WebFingerResultBuilderInterface
{
    public function setSubject(string $subject): self;

    public function addAlias(string $alias): self;

    public function addProperty(string $name, ?string $value = null): self;

    /**
     * @param array<string,string|null> $titles
     * @param array<string,string|null> $properties
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
