<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Invite extends Offer
{
    public const TYPE = 'Invite';

    protected static string $type = self::TYPE;
}
