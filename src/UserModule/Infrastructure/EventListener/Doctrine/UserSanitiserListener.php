<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\EventListener\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Netborg\Fediverse\Api\Shared\Domain\Sanitiser\UsernameSanitiserInterface;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: User::class)]
class UserSanitiserListener
{
    public function __construct(
        private readonly UsernameSanitiserInterface $usernameSanitiser
    ) {
    }

    public function prePersist(User $user, LifecycleEventArgs $event): void
    {
        $username = $user->getUsername();
        $sanitised = $this->usernameSanitiser->sanitise($username);

        $user->setUsername($sanitised);
    }
}
