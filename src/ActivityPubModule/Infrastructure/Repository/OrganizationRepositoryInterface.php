<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Repository;

use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity\Organization;

interface OrganizationRepositoryInterface
{
    public function save(Organization $entity, bool $flush = false): void;

    public function remove(Organization $entity, bool $flush = false): void;

    public function findOneByPreferredUsername(string $username): ?Organization;
}
