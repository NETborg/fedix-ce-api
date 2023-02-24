<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\UserResolver;

use League\Bundle\OAuth2ServerBundle\Event\UserResolveEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

#[AsEventListener(event: 'league.oauth2_server.event.user_resolve', method: 'onUserResolve')]
class PasswordGrantResolver
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserProviderInterface $userProvider,
    ) {
    }

    public function onUserResolve(UserResolveEvent $event): void
    {
        if ('password' !== (string) $event->getGrant()) {
            return;
        }

        $user = $this->userProvider->loadUserByIdentifier($event->getUsername());

        if (!$user instanceof PasswordAuthenticatedUserInterface) {
            return;
        }

        if (!$this->passwordHasher->isPasswordValid($user, $event->getPassword())) {
            return;
        }

        $event->setUser($user);
    }
}
