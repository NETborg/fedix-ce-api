<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Application\Factory;

use Netborg\Fediverse\Api\AuthModule\Domain\Model\Oauth2UserConsent;
use Netborg\Fediverse\Api\AuthModule\Infrastructure\Entity\Oauth2UserConsent as Oauth2UserConsentEntity;
use Netborg\Fediverse\Api\UserModule\Application\Factory\UserFactory;

class Oauth2UserConsentFactory
{
    public function __construct(
        private readonly ClientFactory $clientFactory,
        private readonly UserFactory $userFactory,
    ) {
    }

    public function fromEntity(Oauth2UserConsentEntity $entity): Oauth2UserConsent
    {
        return (new Oauth2UserConsent())
            ->setClient($this->clientFactory->fromLeagueModel($entity->getClient()))
            ->setUser($this->userFactory->toDomainModel($entity->getUser()))
            ->setScopes($entity->getScopes())
            ->setCreatedAt($entity->getCreatedAt()->format(\DateTimeInterface::RFC3339_EXTENDED))
            ->setExpiresAt($entity->getExpiresAt()?->format(\DateTimeInterface::RFC3339_EXTENDED))
        ;
    }
}
