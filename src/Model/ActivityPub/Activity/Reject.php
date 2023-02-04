<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Reject extends Activity
{
    public const TYPE = 'Reject';

    protected static string $type = self::TYPE;
}
