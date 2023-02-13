<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Subject;

class Audio extends Document
{
    public const TYPE = 'Audio';

    protected static string $type = self::TYPE;
}
