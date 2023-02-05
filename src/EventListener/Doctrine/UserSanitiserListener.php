<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\EventListener\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Netborg\Fediverse\Api\Entity\User;
use Netborg\Fediverse\Api\Interfaces\Sanitiser\UsernameSanitiserInterface;

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
