<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Dispatcher;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\EventInterface;

interface EventDispatcherInterface
{
    public function dispatch(EventInterface $event): void;
}
