<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\Person;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Collection\ChangedFieldsCollectionInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\EventInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;

readonly class PersonPostUpdate implements EventInterface
{
    public function __construct(
        private Person $current,
        private ChangedFieldsCollectionInterface $changedFieldsCollection
    ) {
    }

    public function getCurrent(): Person
    {
        return $this->current;
    }

    public function getChangedFieldsCollection(): ChangedFieldsCollectionInterface
    {
        return $this->changedFieldsCollection;
    }
}
