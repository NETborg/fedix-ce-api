<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\Listener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

#[AsEventListener(event: LoginSuccessEvent::class, method: 'onLoginSuccess')]
class LoginListener
{
    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $event->getRequest()->getSession()->set('consent_granted', true);
    }
}
