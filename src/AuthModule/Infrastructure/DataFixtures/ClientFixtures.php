<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use League\Bundle\OAuth2ServerBundle\Manager\ClientManagerInterface;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use League\Bundle\OAuth2ServerBundle\ValueObject\Grant;
use League\Bundle\OAuth2ServerBundle\ValueObject\RedirectUri;
use League\Bundle\OAuth2ServerBundle\ValueObject\Scope;
use Netborg\Fediverse\Api\Tests\AbstractApiTestCase;

class ClientFixtures extends Fixture
{
    public function __construct(
        private readonly ClientManagerInterface $clientManager,
    ) {
    }

    public function load(ObjectManager $manager)
    {
        $client = (new Client(
            AbstractApiTestCase::CLIENT_REGULAR,
            AbstractApiTestCase::CLIENT_REGULAR_ID,
            AbstractApiTestCase::CLIENT_REGULAR_SECRET
        ))
            ->setActive(true)
            ->setAllowPlainTextPkce(false)
            ->setGrants(
                new Grant('password'),
                new Grant('client_credentials'),
                new Grant('authorization_code'),
                new Grant('refresh_token')
            )
            ->setScopes(
                new Scope('client.register_users')
            )
            ->setRedirectUris(
                new RedirectUri('https://zion.social')
            )
        ;

        $this->clientManager->save($client);
    }
}
