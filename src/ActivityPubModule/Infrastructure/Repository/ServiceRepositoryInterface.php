<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Repository;

use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity\Service;

interface ServiceRepositoryInterface
{
    public function save(Service $entity, bool $flush = false): void;

    public function remove(Service $entity, bool $flush = false): void;

    public function findOneByPreferredUsername(string $username): ?Service;
}
