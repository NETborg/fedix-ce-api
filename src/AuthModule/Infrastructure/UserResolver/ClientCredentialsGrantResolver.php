<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\UserResolver;

use League\Bundle\OAuth2ServerBundle\Event\UserResolveEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'league.oauth2_server.event.user_resolve', method: 'onUserResolve')]
class ClientCredentialsGrantResolver
{
    public function onUserResolve(UserResolveEvent $event): void
    {
        if ('client_credentials' !== (string) $event->getGrant()) {
            return;
        }

        dd($event);
    }
}
