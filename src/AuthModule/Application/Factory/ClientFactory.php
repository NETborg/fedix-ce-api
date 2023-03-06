<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Application\Factory;

use League\Bundle\OAuth2ServerBundle\Model\Client as LeagueClient;
use League\Bundle\OAuth2ServerBundle\ValueObject\Grant;
use League\Bundle\OAuth2ServerBundle\ValueObject\RedirectUri;
use League\Bundle\OAuth2ServerBundle\ValueObject\Scope;
use Netborg\Fediverse\Api\AuthModule\Domain\Model\Client;

class ClientFactory
{
    public function fromLeagueModel(LeagueClient $entity, Client $client = null): Client
    {
        $client = $client ?? (new Client());

        return $client
            ->setIdentifier($entity->getIdentifier())
            ->setName($entity->getName())
            ->setSecret($entity->getSecret())
            ->setActive($entity->isActive())
            ->setConfidential($entity->isConfidential())
            ->setAllowPlainTextPkce($entity->isPlainTextPkceAllowed())
            ->setGrants(array_map(
                static fn(Grant $grant) => (string) $grant,
                $entity->getGrants()
            ))
            ->setScopes(array_map(
                static fn(Scope $scope) => (string) $scope,
                $entity->getScopes()
            ))
            ->setRedirectUris(array_map(
                static fn(RedirectUri $redirectUri) => (string) $redirectUri,
                $entity->getRedirectUris()
            ))
        ;
    }
}
