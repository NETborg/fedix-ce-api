<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Offer extends Activity
{
    public const TYPE = 'Offer';

    protected static string $type = self::TYPE;
}