<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Subject;

use Netborg\Fediverse\Api\Model\ActivityPub\LinkType;

class Mention extends LinkType
{
    public const TYPE = 'Mention';

    protected static string $type = self::TYPE;
}
