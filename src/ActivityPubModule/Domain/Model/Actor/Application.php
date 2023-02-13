<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor;

class Application extends AbstractActor
{
    public const TYPE = 'Application';

    protected static string $type = self::TYPE;
}
