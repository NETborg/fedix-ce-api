<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Dislike extends Activity
{
    public const TYPE = 'Dislike';

    protected static string $type = self::TYPE;
}
