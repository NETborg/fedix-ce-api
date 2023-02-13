<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Activity;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Activity;

class Follow extends Activity
{
    public const TYPE = 'Follow';

    protected static string $type = self::TYPE;
}
