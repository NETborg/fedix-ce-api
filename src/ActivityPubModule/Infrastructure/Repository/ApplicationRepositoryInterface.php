<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Repository;

use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity\Application;

interface ApplicationRepositoryInterface
{
    public function save(Application $entity, bool $flush = false): void;

    public function remove(Application $entity, bool $flush = false): void;

    public function findOneByPreferredUsername(string $username): ?Application;
}
