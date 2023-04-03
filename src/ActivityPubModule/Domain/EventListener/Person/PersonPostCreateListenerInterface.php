<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\EventListener\Person;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\Person\PersonPostCreate;

interface PersonPostCreateListenerInterface
{
    public function onPersonCreated(PersonPostCreate $event);
}
