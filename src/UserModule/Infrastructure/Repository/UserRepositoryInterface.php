<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\Repository;

use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user, bool $flush = false): void;

    public function remove(User $entity, bool $flush = false): void;

    public function findOneById(int $id): ?User;

    public function findOneByUsername(string $username): ?User;

    public function findOneByEmail(string $email): ?User;

    public function findOneByUuid(string $uuid): ?User;

    public function findOneByUsernameOrEmail(string $phrase): ?User;

    public function findOneByAnyIdentifier(string $identifier): ?User;
}
