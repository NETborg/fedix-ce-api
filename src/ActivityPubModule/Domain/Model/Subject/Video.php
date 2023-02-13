<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Subject;

class Video extends Document
{
    public const TYPE = 'Video';

    protected static string $type = self::TYPE;
}
