<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Move extends Activity
{
    public const TYPE = 'Move';

    protected static string $type = self::TYPE;
}
