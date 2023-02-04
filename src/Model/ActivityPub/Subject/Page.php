<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub\Subject;

class Page extends Document
{
    public const TYPE = 'Page';

    protected static string $type = self::TYPE;
}
