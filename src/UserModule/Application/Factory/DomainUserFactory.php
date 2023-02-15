<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\Factory;

use Netborg\Fediverse\Api\UserModule\Domain\Model\User as DomainUser;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;

class DomainUserFactory
{
    public function fromEntity(User $entity): DomainUser
    {
        return (new DomainUser())
            ->setUuid($entity->getUuid())
            ->setFirstName($entity->getFirstName())
            ->setLastName($entity->getLastName())
            ->setUsername($entity->getUsername())
            ->setEmail($entity->getEmail())
            ->setPublicKey($entity->getPublicKey())
        ;
    }
}
