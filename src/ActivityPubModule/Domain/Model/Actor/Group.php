<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor;

class Group extends AbstractActor
{
    public const TYPE = 'Group';

    protected static string $type = self::TYPE;
}
