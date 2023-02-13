<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model;

use Symfony\Component\Serializer\Annotation\Ignore;

abstract class IntransitiveActivity extends Activity
{
    public const TYPE = 'IntransitiveActivity';

    protected static string $type = self::TYPE;

    #[Ignore]
    protected string|ObjectType|null $object;
}
