<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Create extends Activity
{
    public const TYPE = 'Create';

    protected static string $type = self::TYPE;
}
