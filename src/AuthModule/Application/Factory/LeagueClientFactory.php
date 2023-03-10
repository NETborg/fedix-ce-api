<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Application\Factory;

use League\Bundle\OAuth2ServerBundle\Manager\ClientManagerInterface;
use League\Bundle\OAuth2ServerBundle\Model\Client as LeagueClient;
use League\Bundle\OAuth2ServerBundle\ValueObject\Grant;
use League\Bundle\OAuth2ServerBundle\ValueObject\RedirectUri;
use League\Bundle\OAuth2ServerBundle\ValueObject\Scope;
use Netborg\Fediverse\Api\AuthModule\Domain\Model\Client;
use Netborg\Fediverse\Api\AuthModule\Infrastructure\Util\ClientHelper;

class LeagueClientFactory
{
    public function __construct(
        private readonly ClientManagerInterface $clientManager,
    ) {
    }

    public function fromClient(Client $client, LeagueClient $leagueClient = null): LeagueClient
    {
        $leagueClient = $leagueClient
            ?? $client->getIdentifier()
            ? $this->clientManager->find($client->getIdentifier())
            : new LeagueClient(
                name: $client->getName(),
                identifier: $client->getIdentifier() ?? ClientHelper::generateIdentifier(),
                secret: $client->isConfidential()
                    ? $client->getSecret() ?? ClientHelper::generateSecret()
                    : null
            );

        return $leagueClient
            ->setName($client->getName())
            ->setActive($client->isActive())
            ->setAllowPlainTextPkce($client->isAllowPlainTextPkce())
            ->setGrants(...array_map(
                static fn(string $grant): Grant => new Grant($grant),
                $client->getGrants()
            ))
            ->setScopes(...array_map(
                static fn(string $scope): Scope => new Scope($scope),
                $client->getScopes()
            ))
            ->setRedirectUris(...array_map(
                static fn(string $redirectUri): RedirectUri => new RedirectUri($redirectUri),
                $client->getRedirectUris()
            ))
        ;
    }
}
