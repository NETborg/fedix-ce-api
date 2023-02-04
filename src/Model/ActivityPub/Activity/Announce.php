<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Announce extends Activity
{
    public const TYPE = 'Announce';

    protected static string $type = self::TYPE;
}
