<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Update extends Activity
{
    public const TYPE = 'Update';

    protected static string $type = self::TYPE;
}
