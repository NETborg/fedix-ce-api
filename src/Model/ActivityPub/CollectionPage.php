<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Model\ActivityPub;

use Netborg\Fediverse\Api\Model\ActivityPub\Subject\Link;

class CollectionPage extends Collection
{
    public const TYPE = 'CollectionPage';

    protected static string $type = self::TYPE;

    protected string|Link|null $partOf = null;
    protected string|Link|null $next = null;
    protected string|Link|null $prev = null;

    public function getPartOf(): string|Link|null
    {
        return $this->partOf;
    }

    public function setPartOf(string|Link|null $partOf): CollectionPage
    {
        $this->partOf = $partOf;

        return $this;
    }

    public function getNext(): string|Link|null
    {
        return $this->next;
    }

    public function setNext(string|Link|null $next): CollectionPage
    {
        $this->next = $next;

        return $this;
    }

    public function getPrev(): string|Link|null
    {
        return $this->prev;
    }

    public function setPrev(string|Link|null $prev): CollectionPage
    {
        $this->prev = $prev;

        return $this;
    }
}
