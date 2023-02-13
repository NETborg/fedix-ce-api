<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Activity;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\IntransitiveActivity;

class Travel extends IntransitiveActivity
{
    public const TYPE = 'Travel';

    protected static string $type = self::TYPE;
}
