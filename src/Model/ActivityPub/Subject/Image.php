<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Subject;

class Image extends Document
{
    public const TYPE = 'Image';

    protected static string $type = self::TYPE;
}
