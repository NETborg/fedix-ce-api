<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class TentativeReject extends Reject
{
    public const TYPE = 'TentativeReject';

    protected static string $type = self::TYPE;
}
