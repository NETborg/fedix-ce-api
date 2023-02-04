<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Subject;

use Netborg\Fediverse\Api\Model\ActivityPub\LinkType;

class Link extends LinkType
{
    public const TYPE = 'Link';

    protected static string $type = self::TYPE;
}
