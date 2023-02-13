<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Subject;

class Page extends Document
{
    public const TYPE = 'Page';

    protected static string $type = self::TYPE;
}
