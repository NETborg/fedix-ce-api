<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use DateTimeInterface;
use Netborg\Fediverse\Api\Model\ActivityPub\IntransitiveActivity;
use Netborg\Fediverse\Api\Model\ActivityPub\LinkType;
use Netborg\Fediverse\Api\Model\ActivityPub\ObjectType;

class Question extends IntransitiveActivity
{
    public const TYPE = 'Question';

    protected static string $type = self::TYPE;

    /** @var array<LinkType|ObjectType|string>|null */
    protected array|null $oneOf = null;

    /** @var array<LinkType|ObjectType|string>|null */
    protected array|null $anyOf = null;
    protected LinkType|ObjectType|DateTimeInterface|string|bool|null $closed = null;

    /**
     * @return array<LinkType|ObjectType|string>|null
     */
    public function getOneOf(): ?array
    {
        return $this->oneOf;
    }

    /**
     * @param array<LinkType|ObjectType|string>|null $oneOf
     */
    public function setOneOf(?array $oneOf): Question
    {
        $this->oneOf = $oneOf;

        return $this;
    }

    /**
     * @return array<LinkType|ObjectType|string>|null
     */
    public function getAnyOf(): ?array
    {
        return $this->anyOf;
    }

    /**
     * @param array<LinkType|ObjectType|string>|null $anyOf
     */
    public function setAnyOf(?array $anyOf): Question
    {
        $this->anyOf = $anyOf;

        return $this;
    }

    public function getClosed(): LinkType|ObjectType|DateTimeInterface|string|bool|null
    {
        return $this->closed;
    }

    public function setClosed(LinkType|ObjectType|DateTimeInterface|string|bool|null $closed): Question
    {
        $this->closed = $closed;

        return $this;
    }
}
