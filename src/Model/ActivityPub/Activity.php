<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub;

abstract class Activity extends ObjectType
{
    public const TYPE = 'Activity';

    protected static string $type = self::TYPE;

    /** @var string|ObjectType|array<ObjectType>|null */
    protected string|ObjectType|array|null $actor = null;
    protected string|ObjectType|null $object = null;
    protected string|ObjectType|LinkType|null $origin = null;
    protected string|ObjectType|LinkType|null $result = null;

    /** @var string|ObjectType|LinkType|array<ObjectType|LinkType|string>|null */
    protected string|ObjectType|LinkType|array|null $target;

    /** @var string|ObjectType|LinkType|array<LinkType|ObjectType|string>|null */
    protected string|ObjectType|LinkType|array|null $instrument;

    public function getActor(): array|string|ObjectType|null
    {
        return $this->actor;
    }

    public function setActor(array|string|ObjectType|null $actor): Activity
    {
        $this->actor = $actor;

        return $this;
    }

    public function getObject(): string|ObjectType|null
    {
        return $this->object;
    }

    public function setObject(string|ObjectType|null $object): Activity
    {
        $this->object = $object;

        return $this;
    }

    public function getOrigin(): LinkType|string|ObjectType|null
    {
        return $this->origin;
    }

    public function setOrigin(LinkType|string|ObjectType|null $origin): Activity
    {
        $this->origin = $origin;

        return $this;
    }

    public function getResult(): LinkType|string|ObjectType|null
    {
        return $this->result;
    }

    public function setResult(LinkType|string|ObjectType|null $result): Activity
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return string|ObjectType|LinkType|array<ObjectType|LinkType|string>|null
     */
    public function getTarget(): LinkType|array|string|ObjectType|null
    {
        return $this->target;
    }

    /**
     * @param string|ObjectType|LinkType|array<ObjectType|LinkType|string>|null $target
     */
    public function setTarget(LinkType|array|string|ObjectType|null $target): Activity
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return string|ObjectType|LinkType|array<ObjectType|LinkType|string>|null
     */
    public function getInstrument(): LinkType|array|string|ObjectType|null
    {
        return $this->instrument;
    }

    /**
     * @param string|ObjectType|LinkType|array<ObjectType|LinkType|string>|null $instrument
     */
    public function setInstrument(LinkType|array|string|ObjectType|null $instrument): Activity
    {
        $this->instrument = $instrument;

        return $this;
    }
}
