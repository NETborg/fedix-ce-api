<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Follow extends Activity
{
    public const TYPE = 'Follow';

    protected static string $type = self::TYPE;
}
