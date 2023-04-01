<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\Person;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\EventInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;

class PersonPostCreate implements EventInterface
{
    public function __construct(
        private Person $person
    ) {
    }

    public function getPerson(): Person
    {
        return $this->person;
    }
}
