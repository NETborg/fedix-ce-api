<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Activity;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Activity;

class Offer extends Activity
{
    public const TYPE = 'Offer';

    protected static string $type = self::TYPE;
}
