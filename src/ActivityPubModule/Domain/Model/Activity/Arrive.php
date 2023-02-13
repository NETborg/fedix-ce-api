<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Activity;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\IntransitiveActivity;

class Arrive extends IntransitiveActivity
{
    public const TYPE = 'Arrive';

    protected static string $type = self::TYPE;
}
