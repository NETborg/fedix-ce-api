<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Accept extends Activity
{
    public const TYPE = 'Accept';

    protected static string $type = self::TYPE;

    protected array $schemaContext = [
        'https://fedix.com/context',
    ];
}
