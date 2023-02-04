<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Activity;

class Block extends Ignore
{
    public const TYPE = 'Block';

    protected static string $type = self::TYPE;
}
