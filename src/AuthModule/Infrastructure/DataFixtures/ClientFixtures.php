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
use Netborg\Fediverse\Api\AuthModule\Domain\Enum\ScopeEnum;
use Netborg\Fediverse\Api\Tests\AuthModule\Enum\NonConfidentialClientEnum;
use Netborg\Fediverse\Api\Tests\AuthModule\Enum\RegularClientEnum;

class ClientFixtures extends Fixture
{
    public function __construct(
        private readonly ClientManagerInterface $clientManager,
    ) {
    }

    public function load(ObjectManager $manager)
    {
        $this->clientManager->save(
            $this->createRegularClient()
        );

        $this->clientManager->save(
            $this->createNonConfidentialClient()
        );
    }

    private function createRegularClient(): Client
    {
        return (new Client(
            RegularClientEnum::NAME,
            RegularClientEnum::IDENTIFIER,
            RegularClientEnum::SECRET
        ))
            ->setActive(true)
            ->setAllowPlainTextPkce(false)
            ->setGrants(
                new Grant('password'),
                new Grant('client_credentials'),
                new Grant('authorization_code'),
                new Grant('refresh_token'),
            )
            ->setScopes(
                new Scope(ScopeEnum::REGISTER_USERS),
                new Scope(ScopeEnum::USER_EMAIL),
            )
            ->setRedirectUris(
                new RedirectUri('https://zion.social')
            )
        ;
    }

    private function createNonConfidentialClient(): Client
    {
        return (new Client(
            NonConfidentialClientEnum::NAME,
            NonConfidentialClientEnum::IDENTIFIER,
            NonConfidentialClientEnum::SECRET
        ))
            ->setActive(true)
            ->setAllowPlainTextPkce(false)
            ->setGrants(
                new Grant('password'),
                new Grant('authorization_code'),
            )
            ->setScopes(
                new Scope(ScopeEnum::REGISTER_USERS),
                new Scope(ScopeEnum::USER_EMAIL),
            )
            ->setRedirectUris(
                new RedirectUri('https://zion.social')
            )
        ;
    }
}
