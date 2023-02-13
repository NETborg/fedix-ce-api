<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Subject;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\ObjectType;

class Event extends ObjectType
{
    public const TYPE = 'Event';

    protected static string $type = self::TYPE;
}
