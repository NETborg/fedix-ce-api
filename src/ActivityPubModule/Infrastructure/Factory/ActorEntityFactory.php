<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Factory;

use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity\Actor;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;

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
