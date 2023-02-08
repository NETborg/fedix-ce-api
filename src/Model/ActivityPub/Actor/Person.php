<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Actor;

class Person extends AbstractActor
{
    public const TYPE = 'Person';

    protected static string $type = self::TYPE;
}
