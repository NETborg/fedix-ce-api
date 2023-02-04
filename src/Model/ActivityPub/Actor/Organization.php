<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Actor;

use Netborg\Fediverse\Api\Model\ActivityPub\ObjectType;

class Organization extends ObjectType
{
    public const TYPE = 'Organization';

    protected static string $type = self::TYPE;
}
