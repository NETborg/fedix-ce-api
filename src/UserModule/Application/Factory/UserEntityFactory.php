<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\Factory;

use Netborg\Fediverse\Api\Shared\Domain\Model\DTO\RegisterUserDTO;
use Netborg\Fediverse\Api\UserModule\Domain\Model\User as DomainUser;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;

class UserEntityFactory
{
    public function fromRegisterUserDTO(RegisterUserDTO $registerUserDTO): User
    {
        return (new User())
            ->setUsername($registerUserDTO->username)
            ->setFirstName($registerUserDTO->firstName)
            ->setLastName($registerUserDTO->lastName)
            ->setEmail($registerUserDTO->email)
            ->setPassword($registerUserDTO->password);
    }

    public function fromDomainUser(DomainUser $domainUser): User
    {
        return (new User())
            ->setUsername($domainUser->getUsername())
            ->setFirstName($domainUser->getFirstName())
            ->setLastName($domainUser->getLastName())
            ->setEmail($domainUser->getEmail())
            ->setPublicKey($domainUser->getPublicKey());
    }
}
