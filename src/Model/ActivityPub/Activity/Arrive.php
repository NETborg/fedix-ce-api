<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\IntransitiveActivity;

class Arrive extends IntransitiveActivity
{
    public const TYPE = 'Arrive';

    protected static string $type = self::TYPE;
}
