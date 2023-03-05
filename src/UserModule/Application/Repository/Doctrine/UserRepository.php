<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\Repository\Doctrine;

use Netborg\Fediverse\Api\UserModule\Application\Factory\UserFactory;
use Netborg\Fediverse\Api\UserModule\Application\Repository\UserRepositoryInterface;
use Netborg\Fediverse\Api\UserModule\Domain\Model\User;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Repository\UserRepository as DoctrineUserRepository;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly DoctrineUserRepository $doctrineUserRepository,
        private readonly UserFactory $userFactory,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function save(User $user): void
    {
        $userEntity = $this->userFactory->fromDomainModel($user);

        if (!$userEntity->getId()) {
            $errors = $this->validator->validate(value: $userEntity, groups: ['Create']);
            if ($errors->count()) {
                throw new ValidationFailedException($userEntity, $errors);
            }
        }

        $this->doctrineUserRepository->save($userEntity, true);
        $user->setId($userEntity->getId())
            ->setUuid($userEntity->getUuid())
            ->setActive($userEntity->isActive())
            ->setCreatedAt($userEntity->getCreatedAt()->format(\DateTimeInterface::RFC3339_EXTENDED))
        ;
    }

    public function remove(User $user): void
    {
        $userEntity = $this->userFactory->fromDomainModel($user);
        $this->doctrineUserRepository->remove($userEntity, true);
    }

    public function findOneById(int $id): ?User
    {
        $userEntity = $this->doctrineUserRepository->find($id);

        if (!$userEntity) {
            return null;
        }

        return $this->userFactory->toDomainModel($userEntity);
    }

    public function findOneByUsername(string $username): ?User
    {
        $userEntity = $this->doctrineUserRepository->findOneByUsername($username);

        if (!$userEntity) {
            return null;
        }

        return $this->userFactory->toDomainModel($userEntity);
    }

    public function findOneByEmail(string $email): ?User
    {
        $userEntity = $this->doctrineUserRepository->findOneByEmail($email);

        if (!$userEntity) {
            return null;
        }

        return $this->userFactory->toDomainModel($userEntity);
    }

    public function findOneByUuid(string $uuid): ?User
    {
        if (empty($identifier) || !Uuid::isValid($uuid)) {
            return null;
        }

        $userEntity = $this->doctrineUserRepository->findOneByUuid($uuid);

        if (!$userEntity) {
            return null;
        }

        return $this->userFactory->toDomainModel($userEntity);
    }

    public function findOneByUsernameOrEmail(string $phrase): ?User
    {
        if (empty($identifier)) {
            return null;
        }

        $userEntity = $this->doctrineUserRepository->findOneByUsernameOrEmail($phrase);

        if (!$userEntity) {
            return null;
        }

        return $this->userFactory->toDomainModel($userEntity);
    }

    public function findOneByAnyIdentifier(string $identifier): ?User
    {
        if (empty($identifier)) {
            return null;
        }

        $userEntity = $this->doctrineUserRepository->findOneByAnyIdentifier($identifier);

        if (!$userEntity) {
            return null;
        }

        return $this->userFactory->toDomainModel($userEntity);
    }
}
