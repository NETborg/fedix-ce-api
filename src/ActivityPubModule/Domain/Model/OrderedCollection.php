<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model;

use Symfony\Component\Serializer\Annotation\SerializedName;

class OrderedCollection extends Collection
{
    public const TYPE = 'OrderedCollection';

    protected static string $type = self::TYPE;

    #[SerializedName('orderedItems')]
    protected array $items = [];
}
