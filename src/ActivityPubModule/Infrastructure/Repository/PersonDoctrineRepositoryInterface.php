<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Repository;

use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity\Person;

interface PersonDoctrineRepositoryInterface
{
    public function save(Person $entity, bool $flush = false): void;

    public function remove(Person $entity, bool $flush = false): void;

    public function findOneByPreferredUsername(string $username): ?Person;

    public function findOneByUuid(string $identifier): ?Person;

    public function findForUser(string $userIdentifier): iterable;

    public function hasForUser(string $userIdentifier): bool;
}
