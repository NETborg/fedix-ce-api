<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class OrderedCollectionPage extends CollectionPage
{
    public const TYPE = 'OrderedCollectionPage';

    protected static string $type = self::TYPE;

    #[SerializedName('orderedItems')]
    protected array $items = [];

    #[Assert\PositiveOrZero]
    protected int|null $startIndex = null;
}
