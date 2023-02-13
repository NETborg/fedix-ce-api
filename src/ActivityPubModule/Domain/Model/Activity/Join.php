<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Activity;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Activity;

class Join extends Activity
{
    public const TYPE = 'Join';

    protected static string $type = self::TYPE;
}
