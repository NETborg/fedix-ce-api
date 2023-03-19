<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LogoutEvent;

#[AsEventListener(event: LogoutEvent::class, method: 'onLogout', priority: -10)]
class LogoutListener
{
    public function __construct(
        private readonly string $frontendHost,
    ) {
    }

    public function onLogout(LogoutEvent $event): void
    {
        $event->setResponse(new RedirectResponse($this->frontendHost));
    }
}
