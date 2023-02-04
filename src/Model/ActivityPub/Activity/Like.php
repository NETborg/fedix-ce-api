<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Like extends Activity
{
    public const TYPE = 'Like';

    protected static string $type = self::TYPE;
}
