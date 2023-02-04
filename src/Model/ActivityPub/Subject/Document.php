<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Subject;

use Netborg\Fediverse\Api\Model\ActivityPub\ObjectType;

class Document extends ObjectType
{
    public const TYPE = 'Document';

    protected static string $type = self::TYPE;
}
