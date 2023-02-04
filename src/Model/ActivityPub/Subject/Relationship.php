<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Subject;

use Netborg\Fediverse\Api\Model\ActivityPub\LinkType;
use Netborg\Fediverse\Api\Model\ActivityPub\ObjectType;

class Relationship extends ObjectType
{
    public const TYPE = 'Relationship';

    protected static string $type = self::TYPE;

    protected ObjectType|LinkType|string|null $subject = null;
    protected ObjectType|LinkType|string|null $object = null;
    protected ObjectType|string|null $relationship = null;

    public function getSubject(): LinkType|string|ObjectType|null
    {
        return $this->subject;
    }

    public function setSubject(LinkType|string|ObjectType|null $subject): Relationship
    {
        $this->subject = $subject;

        return $this;
    }

    public function getObject(): LinkType|string|ObjectType|null
    {
        return $this->object;
    }

    public function setObject(LinkType|string|ObjectType|null $object): Relationship
    {
        $this->object = $object;

        return $this;
    }

    public function getRelationship(): string|ObjectType|null
    {
        return $this->relationship;
    }

    public function setRelationship(string|ObjectType|null $relationship): Relationship
    {
        $this->relationship = $relationship;

        return $this;
    }
}
