<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\Repository\Doctrine;

use Netborg\Fediverse\Api\UserModule\Application\Factory\ActivationLinkFactory;
use Netborg\Fediverse\Api\UserModule\Application\Repository\ActivationLinkRepositoryInterface;
use Netborg\Fediverse\Api\UserModule\Domain\Model\ActivationLink;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Repository\ActivationLinkRepository as DoctrineActivationLinkRepository;

class ActivationLinkRepository implements ActivationLinkRepositoryInterface
{
    public function __construct(
        private readonly DoctrineActivationLinkRepository $repository,
        private readonly ActivationLinkFactory $factory,
    ) {
    }

    public function save(ActivationLink $activationLink): void
    {
        $activationLinkEntity = $this->factory->fromDomainModel($activationLink);
        $this->repository->save($activationLinkEntity, true);
    }

    public function remove(ActivationLink $activationLink): void
    {
        $activationLinkEntity = $this->factory->fromDomainModel($activationLink);
        $this->repository->remove($activationLinkEntity, true);
    }

    public function findOneById(int $id): ?ActivationLink
    {
        $activationLinkEntity = $this->repository->findOneById($id);

        if (!$activationLinkEntity) {
            return null;
        }

        return $this->factory->toDomainModel($activationLinkEntity);
    }

    public function findOneByUuid(string $uuid): ?ActivationLink
    {
        $activationLinkEntity = $this->repository->findOneByUuid($uuid);

        if (!$activationLinkEntity) {
            return null;
        }

        return $this->factory->toDomainModel($activationLinkEntity);
    }
}
