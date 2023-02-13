<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Subject\Link;
use Symfony\Component\Validator\Constraints as Assert;

class Collection extends ObjectType
{
    public const TYPE = 'Collection';

    protected static string $type = self::TYPE;

    protected string|Link|null $first = null;
    protected string|Link|null $current;
    protected string|Link|null $last;

    #[
        Assert\PositiveOrZero()
    ]
    protected int|null $totalItems = null;

    /** @var array<ObjectType|LinkType|string> */
    protected array $items = [];

    public function getFirst(): string|Link|null
    {
        return $this->first;
    }

    public function setFirst(string|Link|null $first): Collection
    {
        $this->first = $first;

        return $this;
    }

    public function getCurrent(): string|Link|null
    {
        return $this->current;
    }

    public function setCurrent(string|Link|null $current): Collection
    {
        $this->current = $current;

        return $this;
    }

    public function getLast(): string|Link|null
    {
        return $this->last;
    }

    public function setLast(string|Link|null $last): Collection
    {
        $this->last = $last;

        return $this;
    }

    public function getTotalItems(): ?int
    {
        return $this->totalItems;
    }

    public function setTotalItems(?int $totalItems): Collection
    {
        $this->totalItems = $totalItems;

        return $this;
    }

    /**
     * @return array<LinkType|ObjectType|string>
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array<LinkType|ObjectType|string> $items
     */
    public function setItems(array $items): Collection
    {
        $this->items = $items;

        return $this;
    }
}
