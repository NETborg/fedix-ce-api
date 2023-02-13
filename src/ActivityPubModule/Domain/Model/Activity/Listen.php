<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Activity;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Activity;

class Listen extends Activity
{
    public const TYPE = 'Listen';

    protected static string $type = self::TYPE;
}
