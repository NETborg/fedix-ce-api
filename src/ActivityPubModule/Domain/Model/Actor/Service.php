<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor;

class Service extends AbstractActor
{
    public const TYPE = 'Service';

    protected static string $type = self::TYPE;
}
