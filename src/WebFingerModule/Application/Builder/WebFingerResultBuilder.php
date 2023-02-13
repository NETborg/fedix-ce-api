<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Application\Builder;

use Netborg\Fediverse\Api\WebFingerModule\Domain\Model\WebFingerLink;
use Netborg\Fediverse\Api\WebFingerModule\Domain\Model\WebFingerProperties;
use Netborg\Fediverse\Api\WebFingerModule\Domain\Model\WebFingerResult;
use Netborg\Fediverse\Api\WebFingerModule\Domain\Model\WebFingerTitles;

class WebFingerResultBuilder implements WebFingerResultBuilderInterface
{
    private ?string $subject = null;
    private array $aliases = [];
    private array $properties = [];
    private array $links = [];

    public function setSubject(string $subject): WebFingerResultBuilderInterface
    {
        $this->subject = $subject;

        return $this;
    }

    public function addAlias(string $alias): WebFingerResultBuilderInterface
    {
        $this->aliases[] = $alias;

        return $this;
    }

    public function addProperty(string $name, ?string $value = null): WebFingerResultBuilderInterface
    {
        $this->properties[$name] = $value;

        return $this;
    }

    public function addLink(
        string $rel,
        string $href = null,
        string $type = null,
        array $titles = [],
        array $properties = []
    ): WebFingerResultBuilderInterface {
        $titles = !empty($titles) ? (new WebFingerTitles())->setProperties($titles) : null;
        $properties = !empty($properties) ? (new WebFingerProperties())->setProperties($properties) : null;

        $this->links[] = (new WebFingerLink())
            ->setRel($rel)
            ->setHref($href)
            ->setType($type)
            ->setTitles($titles)
            ->setProperties($properties)
        ;

        return $this;
    }

    public function build(): WebFingerResult
    {
        $aliases = !empty($this->aliases) ? array_unique($this->aliases) : null;
        $properties = !empty($this->properties) ? (new WebFingerProperties())->setProperties($this->properties) : null;
        $links = !empty($this->links) ? $this->links : null;

        return (new WebFingerResult())
            ->setSubject($this->subject)
            ->setAliases($aliases)
            ->setProperties($properties)
            ->setLinks($links)
        ;
    }
}
