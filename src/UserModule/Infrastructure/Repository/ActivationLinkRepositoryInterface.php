<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\Repository;

use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\ActivationLink;

interface ActivationLinkRepositoryInterface
{
    public function save(ActivationLink $entity, bool $flush = false): void;
    public function remove(ActivationLink $entity, bool $flush = false): void;
    public function findOneById(int $id): ?ActivationLink;
    public function findOneByUuid(string $uuid): ?ActivationLink;
}
