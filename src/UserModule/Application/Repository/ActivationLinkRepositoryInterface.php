<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\Repository;

use Netborg\Fediverse\Api\UserModule\Domain\Model\ActivationLink;

interface ActivationLinkRepositoryInterface
{
    public function save(ActivationLink $activationLink): void;

    public function remove(ActivationLink $activationLink): void;

    public function findOneById(int $id): ?ActivationLink;

    public function findOneByUuid(string $uuid): ?ActivationLink;
}
