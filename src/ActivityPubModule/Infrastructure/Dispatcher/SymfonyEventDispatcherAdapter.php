<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Dispatcher;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Dispatcher\EventDispatcherInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\EventInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcher;

class SymfonyEventDispatcherAdapter implements EventDispatcherInterface
{
    public function __construct(
        private readonly SymfonyEventDispatcher $eventDispatcher
    ) {
    }

    public function dispatch(EventInterface $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }
}
