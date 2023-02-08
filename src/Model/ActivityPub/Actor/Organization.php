<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Actor;

class Organization extends AbstractActor
{
    public const TYPE = 'Organization';

    protected static string $type = self::TYPE;
}
