<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Actor;

use Netborg\Fediverse\Api\Model\ActivityPub\ObjectType;

class Group extends ObjectType
{
    public const TYPE = 'Group';

    protected static string $type = self::TYPE;
}
