<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Actor;

use Netborg\Fediverse\Api\Model\ActivityPub\ObjectType;

class Person extends ObjectType
{
    public const TYPE = 'Person';

    protected static string $type = self::TYPE;
}
