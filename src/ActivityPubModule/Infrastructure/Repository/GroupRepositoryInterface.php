<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Repository;

use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity\Group;

interface GroupRepositoryInterface
{
    public function save(Group $entity, bool $flush = false): void;

    public function remove(Group $entity, bool $flush = false): void;

    public function findOneByPreferredUsername(string $username): ?Group;
}
