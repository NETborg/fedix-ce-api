<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\Repository;

use Netborg\Fediverse\Api\UserModule\Domain\Model\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function remove(User $user): void;

    public function findOneById(int $id): ?User;

    public function findOneByUsername(string $username): ?User;

    public function findOneByEmail(string $email): ?User;

    public function findOneByUuid(string $uuid): ?User;

    public function findOneByUsernameOrEmail(string $phrase): ?User;

    public function findOneByAnyIdentifier(string $identifier): ?User;
}
