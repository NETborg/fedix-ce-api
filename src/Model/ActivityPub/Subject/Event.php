<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Subject;

use Netborg\Fediverse\Api\Model\ActivityPub\ObjectType;

class Event extends ObjectType
{
    public const TYPE = 'Event';

    protected static string $type = self::TYPE;
}
