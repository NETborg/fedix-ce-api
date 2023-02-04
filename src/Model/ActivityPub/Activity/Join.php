<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Join extends Activity
{
    public const TYPE = 'Join';

    protected static string $type = self::TYPE;
}
