<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Delete extends Activity
{
    public const TYPE = 'Delete';

    protected static string $type = self::TYPE;
}
