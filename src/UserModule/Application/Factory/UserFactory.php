<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\Factory;

use Netborg\Fediverse\Api\Shared\Domain\Model\DTO\RegisterUserDTO;
use Netborg\Fediverse\Api\UserModule\Domain\Model\User as DomainModel;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Repository\UserRepository;

class UserFactory
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public function fromRegisterUserDTO(RegisterUserDTO $registerUserDTO): DomainModel
    {
        $name = null;
        if ($registerUserDTO->firstName || $registerUserDTO->lastName) {
            $name = sprintf('%s %s', $registerUserDTO->firstName, $registerUserDTO->lastName);
        }

        return (new DomainModel())
            ->setUsername($registerUserDTO->username)
            ->setFirstName($registerUserDTO->firstName)
            ->setLastName($registerUserDTO->lastName)
            ->setName($name)
            ->setEmail($registerUserDTO->email)
            ->setPassword($registerUserDTO->password);
    }

    public function fromDomainModel(DomainModel $domainModel): User
    {
        $user = match ($domainModel->getId()) {
            null => new User(),
            default => $this->userRepository->findOneById($domainModel->getId()),
        };

        return $user
            ->setUuid($domainModel->getUuid())
            ->setUsername($domainModel->getUsername())
            ->setFirstName($domainModel->getFirstName())
            ->setLastName($domainModel->getLastName())
            ->setName($domainModel->getName())
            ->setEmail($domainModel->getEmail())
            ->setPassword($domainModel->getPassword() ?? $user->getPassword())
            ->setActive((bool) $domainModel->isActive())
            ->setPublicKey($domainModel->getPublicKey())
            ->setCreatedAt($domainModel->getCreatedAt()
               ? \DateTimeImmutable::createFromFormat(
                   \DateTimeInterface::RFC3339_EXTENDED,
                   $domainModel->getCreatedAt())
               : null
            )
        ;
    }

    public function toDomainModel(User $entity): DomainModel
    {
        return (new DomainModel())
            ->setId($entity->getId())
            ->setUuid($entity->getUuid())
            ->setFirstName($entity->getFirstName())
            ->setLastName($entity->getLastName())
            ->setName($entity->getName())
            ->setUsername($entity->getUsername())
            ->setEmail($entity->getEmail())
            ->setPassword($entity->getPassword())
            ->setActive($entity->isActive())
            ->setPublicKey($entity->getPublicKey())
            ->setCreatedAt($entity->getCreatedAt()?->format(\DateTime::RFC3339_EXTENDED))
        ;
    }
}
