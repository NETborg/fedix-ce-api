<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Subject;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\LinkType;

class Mention extends LinkType
{
    public const TYPE = 'Mention';

    protected static string $type = self::TYPE;
}
