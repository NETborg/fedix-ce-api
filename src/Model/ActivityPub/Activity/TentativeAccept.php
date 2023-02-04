<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class TentativeAccept extends Accept
{
    public const TYPE = 'TentativeAccept';

    protected static string $type = self::TYPE;
}
