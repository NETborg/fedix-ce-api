<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Interfaces\Repository;

use Netborg\Fediverse\Api\Entity\Actor;

interface ActorRepositoryInterface
{
    public function save(Actor $user, bool $flush = false): void;

    public function remove(Actor $entity, bool $flush = false): void;

    public function findOneByPreferredUsername(string $username): ?Actor;
}
