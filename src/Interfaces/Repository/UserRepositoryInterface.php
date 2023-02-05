<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Interfaces\Repository;

use Netborg\Fediverse\Api\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user, bool $flush = false): void;

    public function remove(User $entity, bool $flush = false): void;

    public function findByUsername(string $username): ?User;

    public function findByEmail(string $email): ?User;

    public function findByUsernameOrEmail(string $phrase): ?User;
}
