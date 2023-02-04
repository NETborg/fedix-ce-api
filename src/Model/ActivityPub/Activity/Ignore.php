<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Ignore extends Activity
{
    public const TYPE = 'Ignore';

    protected static string $type = self::TYPE;
}
