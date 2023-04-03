<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\EventListener\Person;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\Person\PersonPostUpdate;

interface PersonPostUpdateListenerInterface
{
    public function onPersonUpdated(PersonPostUpdate $event): void;
}
