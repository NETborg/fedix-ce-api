<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Listen extends Activity
{
    public const TYPE = 'Listen';

    protected static string $type = self::TYPE;
}
