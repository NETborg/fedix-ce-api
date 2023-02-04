<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class View extends Activity
{
    public const TYPE = 'View';

    protected static string $type = self::TYPE;
}
