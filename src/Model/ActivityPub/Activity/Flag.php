<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Flag extends Activity
{
    public const TYPE = 'Flag';

    protected static string $type = self::TYPE;
}
