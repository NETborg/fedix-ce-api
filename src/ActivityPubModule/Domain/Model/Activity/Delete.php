<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Activity;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Activity;

class Delete extends Activity
{
    public const TYPE = 'Delete';

    protected static string $type = self::TYPE;
}
