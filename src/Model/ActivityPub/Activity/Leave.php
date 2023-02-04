<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Leave extends Activity
{
    public const TYPE = 'Leave';

    protected static string $type = self::TYPE;
}
