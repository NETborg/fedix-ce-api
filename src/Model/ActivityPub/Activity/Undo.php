<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Undo extends Activity
{
    public const TYPE = 'Undo';

    protected static string $type = self::TYPE;
}
