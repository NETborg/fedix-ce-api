<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Actor;

use Netborg\Fediverse\Api\Model\ActivityPub\ObjectType;

class Service extends ObjectType
{
    public const TYPE = 'Service';

    protected static string $type = self::TYPE;
}
