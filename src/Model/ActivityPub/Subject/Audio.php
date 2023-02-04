<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Subject;

class Audio extends Document
{
    public const TYPE = 'Audio';

    protected static string $type = self::TYPE;
}
