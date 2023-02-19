<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\Factory;

use Netborg\Fediverse\Api\UserModule\Domain\Model\ActivationLink as DomainModel;
use Netborg\Fediverse\Api\UserModule\Domain\Model\User as DomainUser;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\ActivationLink;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Repository\ActivationLinkRepository;
use Symfony\Component\Uid\Uuid;

class ActivationLinkFactory
{
    public function __construct(
        private readonly ActivationLinkRepository $activationLinkRepository,
        private readonly UserFactory $userEntityFactory,
        private readonly string $ttl = 'PT24H'
    ) {
    }

    public function createNew(DomainUser $subject = null): DomainModel
    {
        return (new DomainModel())
            ->setUser($subject)
            ->setUuid(Uuid::v7()->toRfc4122())
            ->setCreatedAt(
                (new \DateTimeImmutable())
                    ->format(\DateTimeInterface::RFC3339_EXTENDED)
            )
            ->setExpiresAt(
                (new \DateTimeImmutable())
                    ->add(new \DateInterval($this->ttl))
                    ->format(\DateTimeInterface::RFC3339_EXTENDED)
            );
    }

    public function fromDomainModel(DomainModel $domainModel): ActivationLink
    {
        $activationLink = match ($domainModel->getId()) {
            null => new ActivationLink(),
            default => $this->activationLinkRepository->findOneById($domainModel->getId()),
        };

        return $activationLink
            ->setUuid($domainModel->getUuid())
            ->setExpiresAt($domainModel->getExpiresAt()
                ? \DateTimeImmutable::createFromFormat(
                    \DateTimeInterface::RFC3339_EXTENDED,
                    $domainModel->getExpiresAt()
                )
                : null
            )
            ->setCreatedAt($domainModel->getExpiresAt()
                ? \DateTimeImmutable::createFromFormat(
                    \DateTimeInterface::RFC3339_EXTENDED,
                    $domainModel->getExpiresAt()
                )
                : null
            )
            ->setUser($domainModel->getUser()
                ? $this->userEntityFactory->fromDomainModel($domainModel->getUser())
                : null
            );
    }

    public function toDomainModel(ActivationLink $entity): DomainModel
    {
        return (new DomainModel())
            ->setId($entity->getId())
            ->setUuid($entity->getUuid())
            ->setExpiresAt($entity->getExpiresAt()->format(\DateTimeInterface::RFC3339_EXTENDED))
            ->setCreatedAt($entity->getCreatedAt()->format(\DateTimeInterface::RFC3339_EXTENDED))
            ->setUser($this->userEntityFactory->toDomainModel($entity->getUser()));
    }
}
