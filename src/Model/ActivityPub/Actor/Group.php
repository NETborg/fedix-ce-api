<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Actor;

class Group extends AbstractActor
{
    public const TYPE = 'Group';

    protected static string $type = self::TYPE;
}
