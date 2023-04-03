<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\EventListener\Person;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\Person\PersonPreCreate;

interface PersonPreCreateListenerInterface
{
    public function onPersonCreate(PersonPreCreate $personPreCreate): void;
}
