<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Repository;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;

interface PersonRepositoryInterface
{
    public function create(Person $person): void;

    public function findAllOwnedBy(string $owner): iterable;

    public function findOneByUuid(string $uuid): ?Person;

    public function findOneByPreferredUsername(string $preferredUsername): ?Person;

    public function update(Person $person): void;

    public function delete(Person|string $person): void;

    public function hasPerson(string $owner): bool;
}
