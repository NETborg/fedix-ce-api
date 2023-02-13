<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Activity;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Activity;

class Leave extends Activity
{
    public const TYPE = 'Leave';

    protected static string $type = self::TYPE;
}
