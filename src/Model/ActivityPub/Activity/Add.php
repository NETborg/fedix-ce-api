<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Add extends Activity
{
    public const TYPE = 'Add';

    protected static string $type = self::TYPE;
}
