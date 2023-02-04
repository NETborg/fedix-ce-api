<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Read extends Activity
{
    public const TYPE = 'Read';

    protected static string $type = self::TYPE;
}
