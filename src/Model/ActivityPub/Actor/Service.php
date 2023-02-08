<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Actor;

class Service extends AbstractActor
{
    public const TYPE = 'Service';

    protected static string $type = self::TYPE;
}
