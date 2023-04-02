<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Application\Factory;

use Netborg\Fediverse\Api\AuthModule\Domain\Model\Oauth2UserConsent;
use Netborg\Fediverse\Api\AuthModule\Infrastructure\Entity\Oauth2UserConsent as Oauth2UserConsentEntity;
use Netborg\Fediverse\Api\UserModule\Application\Factory\UserFactory;

class Oauth2UserConsentEntityFactory
{
    public function __construct(
        private readonly UserFactory $userFactory,
        private readonly LeagueClientFactory $leagueClientFactory,
    ) {
    }

    public function fromModel(Oauth2UserConsent $model, Oauth2UserConsentEntity $entity = null): Oauth2UserConsentEntity
    {
        $entity = $entity ?? new Oauth2UserConsentEntity();

        if ($model->getCreatedAt()) {
            $entity->setCreatedAt(\DateTimeImmutable::createFromFormat(
                \DateTimeInterface::RFC3339_EXTENDED,
                $model->getCreatedAt()
            ));
        }

        return $entity
            ->setClient($this->leagueClientFactory->fromClient($model->getClient()))
            ->setUser($this->userFactory->fromDomainModel($model->getUser()))
            ->setScopes($model->getScopes())
            ->setExpiresAt($model->getExpiresAt()
                ? \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339_EXTENDED, $model->getExpiresAt())
                : null
            )
        ;
    }
}
