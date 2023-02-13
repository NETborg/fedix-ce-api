<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Subject;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\LinkType;

class Link extends LinkType
{
    public const TYPE = 'Link';

    protected static string $type = self::TYPE;
}
