<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\Repository;

use League\Bundle\OAuth2ServerBundle\Repository\AccessTokenRepository as LeagueAccessTokenRepository;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Netborg\Fediverse\Api\AuthModule\Infrastructure\Entity\AccessToken;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    public function __construct(
        private readonly LeagueAccessTokenRepository $baseAccessTokenRepository,
    ) {
    }

    /** @param int|string|null $userIdentifier */
    public function getNewToken(
        ClientEntityInterface $clientEntity,
        array $scopes,
        $userIdentifier = null
    ): AccessTokenEntityInterface {
        $accessToken = new AccessToken();
        $accessToken->setClient($clientEntity);
        $accessToken->setUserIdentifier($userIdentifier);

        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }

        return $accessToken;
    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $this->baseAccessTokenRepository->persistNewAccessToken($accessTokenEntity);
    }

    /** @param string $tokenId */
    public function revokeAccessToken($tokenId)
    {
        $this->baseAccessTokenRepository->revokeAccessToken($tokenId);
    }

    /** @param string $tokenId */
    public function isAccessTokenRevoked($tokenId): bool
    {
        return $this->baseAccessTokenRepository->isAccessTokenRevoked($tokenId);
    }
}
