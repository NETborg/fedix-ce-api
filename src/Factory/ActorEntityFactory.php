<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Factory;

use Netborg\Fediverse\Api\Entity\Actor;
use Netborg\Fediverse\Api\Entity\User;
use Netborg\Fediverse\Api\Interfaces\Factory\ActorEntityFactoryInterface;

class ActorEntityFactory implements ActorEntityFactoryInterface
{
    public function createFromUserEntity(User $user, string $type): Actor
    {
        return (new Actor())
            ->setType($type)
            ->setName($user->getName() ?? ucfirst($user->getUsername()))
            ->setPreferredUsername($user->getUsername())
            ->setPublicKey($user->getPublicKey())
        ;
    }
}
