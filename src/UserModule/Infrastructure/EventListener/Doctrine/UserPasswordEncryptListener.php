<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\EventListener\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: User::class)]
class UserPasswordEncryptListener
{
    public function __construct(
        private readonly PasswordHasherInterface $passwordHasher,
    ) {
    }

    public function prePersist(User $user, LifecycleEventArgs $event): void
    {
        $plainPassword = $user->getPassword();
        $hashed = $this->passwordHasher->hash($plainPassword);

        $user->setPassword($hashed);
    }
}
