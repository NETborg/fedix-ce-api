<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\AuthModule\Application;

use League\Bundle\OAuth2ServerBundle\Manager\ClientManagerInterface;
use League\Bundle\OAuth2ServerBundle\Model\Client;
use League\Bundle\OAuth2ServerBundle\ValueObject\Grant;
use League\Bundle\OAuth2ServerBundle\ValueObject\RedirectUri;
use League\Bundle\OAuth2ServerBundle\ValueObject\Scope;
use Netborg\Fediverse\Api\AuthModule\Domain\Enum\ScopeEnum;
use Netborg\Fediverse\Api\Tests\AbstractApiTestCase;
use Netborg\Fediverse\Api\Tests\AuthModule\Enum\NonConfidentialClientEnum;
use Netborg\Fediverse\Api\Tests\AuthModule\Enum\RegularClientEnum;
use Netborg\Fediverse\Api\Tests\UserModule\Enum\RegularUserEnum;

class Oauth2PasswordGrantFlowTest extends AbstractApiTestCase
{
    public function testSuccessfulPasswordGrantCase(): void
    {
        $client = static::createClient();

        $clientManager = $this->getContainer()->get(ClientManagerInterface::class);
        $appClient = $clientManager->find(RegularClientEnum::IDENTIFIER);

        $payload = [
            'grant_type' => 'password',
            'client_id' => $appClient->getIdentifier(),
            'client_secret' => $appClient->getSecret(),
            'scope' => ScopeEnum::USER_EMAIL,
            'username' => RegularUserEnum::USERNAME,
            'password' => RegularUserEnum::PASSWORD,
        ];

        $expectedResponse = <<<TXT
{
    "token_type": "Bearer",
    "expires_in": 3600,
    "access_token": "@string@",
    "refresh_token": "@string@"
}
TXT;

        $client->jsonRequest('POST', '/api/oauth2/token', $payload);

        $this->assertResponseIsSuccessful();
        $this->assertMatchesPattern($expectedResponse, $client->getResponse()->getContent());
    }

    public function testSuccessfulNoRedirectUriCase(): void
    {
        $client = static::createClient();

        $clientManager = $this->getContainer()->get(ClientManagerInterface::class);
        $appClient = $clientManager->find(RegularClientEnum::IDENTIFIER);
        $appClient->setRedirectUris();
        $clientManager->save($appClient);

        $payload = [
            'grant_type' => 'password',
            'client_id' => $appClient->getIdentifier(),
            'client_secret' => $appClient->getSecret(),
            'scope' => ScopeEnum::USER_EMAIL,
            'username' => RegularUserEnum::USERNAME,
            'password' => RegularUserEnum::PASSWORD,
        ];

        $expectedResponse = <<<TXT
{
    "token_type": "Bearer",
    "expires_in": 3600,
    "access_token": "@string@",
    "refresh_token": "@string@"
}
TXT;

        $client->jsonRequest('POST', '/api/oauth2/token', $payload);

        $this->assertResponseIsSuccessful();
        $this->assertMatchesPattern($expectedResponse, $client->getResponse()->getContent());
    }

    public function testInvalidUsernameCase(): void
    {
        $client = static::createClient();

        $clientManager = $this->getContainer()->get(ClientManagerInterface::class);
        $appClient = $clientManager->find(RegularClientEnum::IDENTIFIER);

        $payload = [
            'grant_type' => 'password',
            'client_id' => $appClient->getIdentifier(),
            'client_secret' => $appClient->getSecret(),
            'scope' => ScopeEnum::USER_EMAIL,
            'username' => 'invalid_username',
            'password' => RegularUserEnum::PASSWORD,
        ];

        $expectedResponse = <<<TXT
{
  "error": "User not found!",
  "code": 404
}
TXT;

        $client->jsonRequest('POST', '/api/oauth2/token', $payload);

        $this->assertResponseStatusCodeSame(404);
        $this->assertMatchesPattern($expectedResponse, $client->getResponse()->getContent());
    }

    public function testNonConfidentialClientCase(): void
    {
        $client = static::createClient();

        $clientManager = $this->getContainer()->get(ClientManagerInterface::class);
        $appClient = $clientManager->find(NonConfidentialClientEnum::IDENTIFIER);

        $payload = [
            'grant_type' => 'password',
            'client_id' => $appClient->getIdentifier(),
            'scope' => ScopeEnum::USER_EMAIL,
            'username' => RegularUserEnum::USERNAME,
            'password' => RegularUserEnum::PASSWORD,
        ];

        $expectedResponse = <<<TXT
{
    "token_type": "Bearer",
    "expires_in": 3600,
    "access_token": "@string@",
    "refresh_token": "@string@"
}
TXT;

        $client->jsonRequest('POST', '/api/oauth2/token', $payload);

        $this->assertResponseIsSuccessful();
        $this->assertMatchesPattern($expectedResponse, $client->getResponse()->getContent());
    }

    public function testInvalidGrantTypeCase(): void
    {
        $client = static::createClient();

        $payload = [
            'grant_type' => 'other_grant',
            'client_id' => RegularClientEnum::IDENTIFIER,
            'client_secret' => RegularClientEnum::SECRET,
            'scope' => ScopeEnum::USER_EMAIL,
            'username' => RegularUserEnum::USERNAME,
            'password' => RegularUserEnum::PASSWORD,
        ];

        $expectedResponse = <<<TXT
{
    "error": "unsupported_grant_type",
    "error_description": "The authorization grant type is not supported by the authorization server.",
    "hint": "Check that all required parameters have been provided",
    "message": "The authorization grant type is not supported by the authorization server."
}
TXT;

        $client->jsonRequest('POST', '/api/oauth2/token', $payload);

        $this->assertResponseStatusCodeSame(400);
        $this->assertMatchesPattern($expectedResponse, $client->getResponse()->getContent());
    }

    public function testInvalidScopeCase(): void
    {
        $client = static::createClient();

        $payload = [
            'grant_type' => 'password',
            'client_id' => RegularClientEnum::IDENTIFIER,
            'client_secret' => RegularClientEnum::SECRET,
            'scope' => sprintf('%s %s', ScopeEnum::USER_EMAIL, ScopeEnum::USER_POSTS_WRITE),
            'username' => RegularUserEnum::USERNAME,
            'password' => RegularUserEnum::PASSWORD,
        ];

        $expectedResponse = <<<TXT
{
  "error": "invalid_scope",
  "error_description": "The requested scope is invalid, unknown, or malformed",
  "hint": "Check the `user.posts_write` scope",
  "message": "The requested scope is invalid, unknown, or malformed"
}
TXT;

        $client->jsonRequest('POST', '/api/oauth2/token', $payload);

        $this->assertResponseStatusCodeSame(400);
        $this->assertMatchesPattern($expectedResponse, $client->getResponse()->getContent());
    }

    public function testInvalidClientIdCase(): void
    {
        $client = static::createClient();

        $payload = [
            'grant_type' => 'password',
            'client_id' => 'invalidClientId',
            'client_secret' => RegularClientEnum::SECRET,
            'scope' => ScopeEnum::USER_EMAIL,
            'username' => RegularUserEnum::USERNAME,
            'password' => RegularUserEnum::PASSWORD,
        ];

        $expectedResponse = <<<TXT
{
  "error": "invalid_client",
  "error_description": "Client authentication failed",
  "message": "Client authentication failed"
}
TXT;

        $client->jsonRequest('POST', '/api/oauth2/token', $payload);

        $this->assertResponseStatusCodeSame(401);
        $this->assertMatchesPattern($expectedResponse, $client->getResponse()->getContent());
    }

    public function testInvalidClientCredentialCase(): void
    {
        $client = static::createClient();

        $payload = [
            'grant_type' => 'password',
            'client_id' => RegularClientEnum::IDENTIFIER,
            'client_secret' => 'InvalidSecret',
            'scope' => ScopeEnum::USER_EMAIL,
            'username' => RegularUserEnum::USERNAME,
            'password' => RegularUserEnum::PASSWORD,
        ];

        $expectedResponse = <<<TXT
{
  "error": "invalid_client",
  "error_description": "Client authentication failed",
  "message": "Client authentication failed"
}
TXT;

        $client->jsonRequest('POST', '/api/oauth2/token', $payload);

        $this->assertResponseStatusCodeSame(401);
        $this->assertMatchesPattern($expectedResponse, $client->getResponse()->getContent());
    }

    public function testNonApplicableGrantTypeCase(): void
    {
        $client = static::createClient();

        $clientManager = $this->getContainer()->get(ClientManagerInterface::class);
        $appClient = $this->createNonPasswordGrantClient();
        $clientManager->save($appClient);

        $payload = [
            'grant_type' => 'password',
            'client_id' => $appClient->getIdentifier(),
            'client_secret' => $appClient->getSecret(),
            'scope' => ScopeEnum::USER_EMAIL,
            'username' => RegularUserEnum::USERNAME,
            'password' => RegularUserEnum::PASSWORD,
        ];

        $expectedResponse = <<<TXT
{
  "error": "invalid_client",
  "error_description": "Client authentication failed",
  "message": "Client authentication failed"
}
TXT;

        $client->jsonRequest('POST', '/api/oauth2/token', $payload);

        $this->assertResponseStatusCodeSame(401);
        $this->assertMatchesPattern($expectedResponse, $client->getResponse()->getContent());
    }

    public function testInactiveClientCase(): void
    {
        $client = static::createClient();

        $clientManager = $this->getContainer()->get(ClientManagerInterface::class);
        $appClient = $clientManager->find(RegularClientEnum::IDENTIFIER);
        $appClient->setActive(false);
        $clientManager->save($appClient);

        $payload = [
            'grant_type' => 'password',
            'client_id' => $appClient->getIdentifier(),
            'client_secret' => $appClient->getSecret(),
            'scope' => ScopeEnum::USER_EMAIL,
            'username' => RegularUserEnum::USERNAME,
            'password' => RegularUserEnum::PASSWORD,
        ];

        $expectedResponse = <<<TXT
{
  "error": "invalid_client",
  "error_description": "Client authentication failed",
  "message": "Client authentication failed"
}
TXT;

        $client->jsonRequest('POST', '/api/oauth2/token', $payload);

        $this->assertResponseStatusCodeSame(401);
        $this->assertMatchesPattern($expectedResponse, $client->getResponse()->getContent());
    }

    private function createNonPasswordGrantClient(): Client
    {
        return (new Client(
            'Non Password Grant',
            '11111111112222222222333333333344',
            'SuperSecret'
        ))
            ->setActive(true)
            ->setAllowPlainTextPkce(false)
            ->setGrants(new Grant('client_credentials'))
            ->setScopes(new Scope(ScopeEnum::REGISTER_USERS), new Scope(ScopeEnum::USER_EMAIL))
            ->setRedirectUris(new RedirectUri('https://somewhere.com'));
    }
}
