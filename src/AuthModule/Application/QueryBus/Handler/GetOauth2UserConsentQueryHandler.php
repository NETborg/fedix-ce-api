<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Application\QueryBus\Handler;

use Doctrine\ORM\EntityManagerInterface;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use Netborg\Fediverse\Api\AuthModule\Application\Exception\InvalidArgumentException;
use Netborg\Fediverse\Api\AuthModule\Application\Factory\Oauth2UserConsentFactory;
use Netborg\Fediverse\Api\AuthModule\Application\Model\DTO\GetUserConsentDTO;
use Netborg\Fediverse\Api\AuthModule\Application\QueryBus\Query\GetOauth2UserConsentQuery;
use Netborg\Fediverse\Api\AuthModule\Domain\Model\Oauth2UserConsent;
use Netborg\Fediverse\Api\AuthModule\Infrastructure\Repository\Oauth2UserConsentRepository;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\Query\QueryInterface;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryHandlerInterface;
use Netborg\Fediverse\Api\UserModule\Domain\Model\User;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Repository\UserRepository;

class GetOauth2UserConsentQueryHandler implements QueryHandlerInterface
{
    public const NAME = 'oauth2_user_consent.get';

    public function __construct(
        private readonly Oauth2UserConsentRepository $consentRepository,
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly Oauth2UserConsentFactory $oauth2UserConsentFactory,
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function supports(string $query, string $subjectType): bool
    {
        return GetOauth2UserConsentQuery::NAME === $query
            && GetUserConsentDTO::class === $subjectType;
    }

    public function handle(QueryInterface $query): ?Oauth2UserConsent
    {
        /** @var GetUserConsentDTO $dto */
        $dto = $query->getSubject();
        $user = null;
        $client = null;

        if (is_string($dto->user)) {
            $user = $this->userRepository->findOneByAnyIdentifier($dto->user);
        }
        if ($dto->user instanceof User) {
            $user = $this->userRepository->findOneByUuid($dto->user->getUuid());
        }
        if (!$user) {
            throw InvalidArgumentException::user();
        }

        if (is_string($dto->client)) {
            $client = $this->entityManager->getRepository(Client::class)->findOneByIdentifier($dto->client);
        }
        if ($dto->client instanceof Client) {
            $client = $dto->client;
        }
        if (!$client) {
            throw InvalidArgumentException::client();
        }

        $entity = $this->consentRepository->findOneByUserAndClient($user, $client);

        return $entity ? $this->oauth2UserConsentFactory->fromEntity($entity) : null;
    }
}
