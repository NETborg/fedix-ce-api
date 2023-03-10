<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests;

use League\Bundle\OAuth2ServerBundle\Manager\ClientManagerInterface;
use League\Bundle\OAuth2ServerBundle\Entity\Client as LeagueClientEntity;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use League\Bundle\OAuth2ServerBundle\ValueObject\Grant;
use League\Bundle\OAuth2ServerBundle\ValueObject\RedirectUri;
use League\Bundle\OAuth2ServerBundle\ValueObject\Scope;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Netborg\Fediverse\Api\AuthModule\Domain\Enum\ScopeEnum;
use Netborg\Fediverse\Api\AuthModule\Infrastructure\Util\ClientHelper;
use Netborg\Fediverse\Api\Tests\AuthModule\Enum\RegularClientEnum;
use Symfony\Component\Uid\Uuid;

trait ClientLoggerTrait
{
    protected function createRegularClient(): Client
    {
        return $this->createClientModel(
            name: RegularClientEnum::NAME,
            identifier: RegularClientEnum::IDENTIFIER,
            secret: RegularClientEnum::SECRET,
            grants: [
                new Grant('password'),
                new Grant('client_credentials'),
                new Grant('authorization_code'),
                new Grant('refresh_token'),
            ],
            scopes: [
                new Scope(ScopeEnum::REGISTER_USERS),
                new Scope(ScopeEnum::USER_EMAIL),
            ],
            redirectUris: [
                new RedirectUri('http://zion.social')
            ]
        );
    }

    protected function createClientModel(
        string $name,
        string $identifier = null,
        string $secret = null,
        bool $active = true,
        array $grants = [],
        array $scopes = [],
        array $redirectUris = [],
        bool $allowPlainTextPkce = false,
    ): Client {
        return (new Client(
            name: $name,
            identifier: $identifier ?? ClientHelper::generateIdentifier(),
            secret: $secret ?? ClientHelper::generateSecret()
        ))
            ->setActive($active)
            ->setAllowPlainTextPkce($allowPlainTextPkce)
            ->setGrants(...array_map(static function ($grant) {
                return $grant instanceof Grant
                    ? $grant
                    : new Grant((string) $grant);
            }, $grants))
            ->setScopes(...array_map(static function ($scope) {
                return $scope instanceof Scope
                    ? $scope
                    : new Scope((string) $scope);
            }, $scopes))
            ->setRedirectUris(...array_map(static function ($redirectUri) {
                return $redirectUri instanceof RedirectUri
                    ? $redirectUri
                    : new RedirectUri((string) $redirectUri);
            }, $redirectUris))
        ;
    }

    protected function createAccessToken(Client $client, string $userIdentifier = null): string
    {
        $this->saveClient($client);
        $accessTokenRepository = $this->getContainer()->get(AccessTokenRepositoryInterface::class);

        $clientEntity = new LeagueClientEntity();
        $clientEntity->setName($client->getName());
        $clientEntity->setIdentifier($client->getIdentifier());
        $clientEntity->setConfidential($client->isConfidential());
        $clientEntity->setAllowPlainTextPkce($client->isPlainTextPkceAllowed());
        $clientEntity->setRedirectUri(array_map(
            static fn ($redirectUri) => (string) $redirectUri,
            $client->getScopes()
        ));

        $scopeEntities = array_map(static function ($scope) {
            $scopeEntity = new \League\Bundle\OAuth2ServerBundle\Entity\Scope();
            $scopeEntity->setIdentifier((string) $scope);

            return $scopeEntity;
        }, $client->getScopes());

        $accessToken = $accessTokenRepository->getNewToken($clientEntity, $scopeEntities, $userIdentifier);
        $accessToken->setIdentifier((string) Uuid::v7());
        $accessToken->setExpiryDateTime((new \DateTimeImmutable())->add(new \DateInterval('PT1H')));
        $accessToken->setPrivateKey(new CryptKey(
            $this->getContainer()->getParameter('oauth2_config.private_key_location'),
            $this->getContainer()->getParameter('oauth2_config.passphrase'),
            false
        ));

        $accessTokenRepository->persistNewAccessToken($accessToken);

        return (string) $accessToken;
    }

    protected function saveClient(Client $client): void
    {
        if (empty($client->getIdentifier())) {
            return;
        }

        $clientManager = $this->getContainer()->get(ClientManagerInterface::class);

        if (!$clientManager->find($client->getIdentifier())) {
            $clientManager->save($client);
        }
    }
}
