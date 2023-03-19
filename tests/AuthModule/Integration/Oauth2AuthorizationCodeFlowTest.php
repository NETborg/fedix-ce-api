<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\AuthModule\Integration;

use Netborg\Fediverse\Api\AuthModule\Application\CommandBus\Command\CreateOauth2UserConsentCommand;
use Netborg\Fediverse\Api\AuthModule\Application\Model\DTO\GetUserConsentDTO;
use Netborg\Fediverse\Api\AuthModule\Application\QueryBus\Query\GetOauth2ClientQuery;
use Netborg\Fediverse\Api\AuthModule\Application\QueryBus\Query\GetOauth2UserConsentQuery;
use Netborg\Fediverse\Api\AuthModule\Domain\Enum\ScopeEnum;
use Netborg\Fediverse\Api\AuthModule\Domain\Model\Client;
use Netborg\Fediverse\Api\AuthModule\Domain\Model\Oauth2UserConsent;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandBusInterface;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryBusInterface;
use Netborg\Fediverse\Api\Tests\AbstractApiTestCase;
use Netborg\Fediverse\Api\Tests\AuthModule\Enum\RegularClientEnum;
use Netborg\Fediverse\Api\Tests\UserModule\Enum\RegularUserEnum;
use Netborg\Fediverse\Api\UserModule\Application\QueryBus\Query\GetUserQuery;
use Netborg\Fediverse\Api\UserModule\Domain\Model\User;

class Oauth2AuthorizationCodeFlowTest extends AbstractApiTestCase
{
    public function testSuccessfulFullAcceptanceFlow()
    {
        $client = static::createClient();

        $client->request('GET', '/oauth2/authorize', [
            'response_type' => 'code',
            'client_id' => RegularClientEnum::IDENTIFIER,
            'redirect_uri' => RegularClientEnum::REDIRECT_URI,
            'scope' => ScopeEnum::USER_EMAIL,
            'state' => '1234abcd',
        ]);

        $this->assertResponseRedirects('http://localhost/login');
        $crawler = $client->followRedirect();

        $this->assertSelectorExists('form');

        $form = $crawler->selectButton('Sign in')->form(method: 'post');
        $form['_username'] = RegularUserEnum::EMAIL;
        $form['_password'] = RegularUserEnum::PASSWORD;

        $client->submit($form);

        $this->assertResponseRedirects('http://localhost/oauth2/authorize?client_id=11111111111111111111111111111111&redirect_uri=https%3A%2F%2Fzion.social%2Fauth&response_type=code&scope=user.email&state=1234abcd');
        $client->followRedirect();

        $this->assertResponseRedirects('/oauth2/consent?client_id=11111111111111111111111111111111&redirect_uri=https://zion.social/auth&response_type=code&scope=user.email&state=1234abcd');
        $crawler = $client->followRedirect();

        $this->assertSelectorExists('form');

        $form = $crawler->selectButton('Grant access')->form(method: 'post');
        $form['consent'] = 'yes';
        $client->submit($form);

        $this->assertResponseRedirects('/oauth2/authorize?client_id=11111111111111111111111111111111&redirect_uri=https://zion.social/auth&response_type=code&scope=user.email&state=1234abcd');

        $userConsent = $this->getExistingUserConsent(
            RegularClientEnum::IDENTIFIER,
            RegularUserEnum::USERNAME
        );

        $this->assertNotEmpty($userConsent);

        $client->followRedirect();
        $crawler = $client->followRedirect();

        $expectedUriWithCode = 'https://zion.social/auth?code=@string@&state=1234abcd';
        $uriWithCode = $crawler->getUri();

        $this->assertMatchesPattern($expectedUriWithCode, $uriWithCode);

        preg_match('/https:\\/\\/zion\.social\\/auth\?code=([^&]+)/', $uriWithCode, $matches);
        $code = $matches[1] ?? null;

        $this->assertNotEmpty($code);

        $authCodePayload = [
            'grant_type' => 'authorization_code',
            'client_id' => RegularClientEnum::IDENTIFIER,
            'client_secret' => RegularClientEnum::SECRET,
            'redirect_uri' => RegularClientEnum::REDIRECT_URI,
            'code' => $code
        ];

        $expectedResponse = <<<TXT
{
    "token_type": "Bearer",
    "expires_in": 3600,
    "access_token": "@string@",
    "refresh_token": "@string@"
}
TXT;

        $client->jsonRequest('POST', '/api/oauth2/token', $authCodePayload);

        $this->assertResponseIsSuccessful();
        $this->assertMatchesPattern($expectedResponse, $client->getResponse()->getContent());
    }

    public function testUserRejectsConsentFlow()
    {
        $client = static::createClient();

        $client->request('GET', '/oauth2/authorize', [
            'response_type' => 'code',
            'client_id' => RegularClientEnum::IDENTIFIER,
            'redirect_uri' => RegularClientEnum::REDIRECT_URI,
            'scope' => ScopeEnum::USER_EMAIL,
            'state' => '1234abcd',
        ]);

        $this->assertResponseRedirects('http://localhost/login');
        $crawler = $client->followRedirect();

        $this->assertSelectorExists('form');

        $form = $crawler->selectButton('Sign in')->form(method: 'post');
        $form['_username'] = RegularUserEnum::EMAIL;
        $form['_password'] = RegularUserEnum::PASSWORD;

        $client->submit($form);

        $this->assertResponseRedirects('http://localhost/oauth2/authorize?client_id=11111111111111111111111111111111&redirect_uri=https%3A%2F%2Fzion.social%2Fauth&response_type=code&scope=user.email&state=1234abcd');
        $client->followRedirect();

        $this->assertResponseRedirects('/oauth2/consent?client_id=11111111111111111111111111111111&redirect_uri=https://zion.social/auth&response_type=code&scope=user.email&state=1234abcd');
        $crawler = $client->followRedirect();

        $this->assertSelectorExists('form');

        $form = $crawler->selectButton('Grant access')->form(method: 'post');
        $form['consent'] = 'no';
        $client->submit($form);

        $userConsent = $this->getExistingUserConsent(
            RegularClientEnum::IDENTIFIER,
            RegularUserEnum::USERNAME
        );

        $this->assertEmpty($userConsent);
        $this->assertResponseRedirects('/oauth2/authorize?client_id=11111111111111111111111111111111&redirect_uri=https://zion.social/auth&response_type=code&scope=user.email&state=1234abcd');

        $client->followRedirect();
        $crawler = $client->followRedirect();

        $expectedUriWithCode = 'https://zion.social/auth?state=1234abcd&error=access_denied&error_description=@string@&hint=@string@';
        $uriWithCode = $crawler->getUri();

        $this->assertMatchesPattern($expectedUriWithCode, $uriWithCode);
    }

    public function testSuccessfulExistingConsentFlow()
    {
        $client = static::createClient();
        $clientId = RegularClientEnum::IDENTIFIER;
        $userId = RegularUserEnum::EMAIL;
        $scope = [
            ScopeEnum::USER_EMAIL
        ];

        $this->createUserConsent($clientId, $userId, $scope);

        $client->request('GET', '/oauth2/authorize', [
            'response_type' => 'code',
            'client_id' => $clientId,
            'redirect_uri' => RegularClientEnum::REDIRECT_URI,
            'scope' => implode(',', $scope),
            'state' => '1234abcd',
        ]);

        $this->assertResponseRedirects('http://localhost/login');
        $crawler = $client->followRedirect();

        $this->assertSelectorExists('form');

        $form = $crawler->selectButton('Sign in')->form(method: 'post');
        $form['_username'] = $userId;
        $form['_password'] = RegularUserEnum::PASSWORD;

        $client->submit($form);
        $this->assertResponseRedirects('http://localhost/oauth2/authorize?client_id=11111111111111111111111111111111&redirect_uri=https%3A%2F%2Fzion.social%2Fauth&response_type=code&scope=user.email&state=1234abcd');

        $client->followRedirect();
        $this->assertResponseRedirects('/oauth2/consent?client_id=11111111111111111111111111111111&redirect_uri=https://zion.social/auth&response_type=code&scope=user.email&state=1234abcd');

        $client->followRedirect();
        $this->assertResponseRedirects('/oauth2/authorize?client_id=11111111111111111111111111111111&redirect_uri=https://zion.social/auth&response_type=code&scope=user.email&state=1234abcd');

        $userConsent = $this->getExistingUserConsent(
            RegularClientEnum::IDENTIFIER,
            RegularUserEnum::USERNAME
        );

        $this->assertNotEmpty($userConsent);

        $client->followRedirect();
        $crawler = $client->followRedirect();

        $expectedUriWithCode = 'https://zion.social/auth?code=@string@&state=1234abcd';
        $uriWithCode = $crawler->getUri();

        $this->assertMatchesPattern($expectedUriWithCode, $uriWithCode);

        preg_match('/https:\\/\\/zion\.social\\/auth\?code=([^&]+)/', $uriWithCode, $matches);
        $code = $matches[1] ?? null;

        $this->assertNotEmpty($code);

        $authCodePayload = [
            'grant_type' => 'authorization_code',
            'client_id' => RegularClientEnum::IDENTIFIER,
            'client_secret' => RegularClientEnum::SECRET,
            'redirect_uri' => RegularClientEnum::REDIRECT_URI,
            'code' => $code
        ];

        $expectedResponse = <<<TXT
{
    "token_type": "Bearer",
    "expires_in": 3600,
    "access_token": "@string@",
    "refresh_token": "@string@"
}
TXT;

        $client->jsonRequest('POST', '/api/oauth2/token', $authCodePayload);

        $this->assertResponseIsSuccessful();
        $this->assertMatchesPattern($expectedResponse, $client->getResponse()->getContent());
    }

    public function testSuccessfulPartialConsentFlow()
    {
        $client = static::createClient();
        $clientId = RegularClientEnum::IDENTIFIER;
        $userId = RegularUserEnum::EMAIL;
        $scope = [
            ScopeEnum::USER_EMAIL
        ];

        $this->createUserConsent($clientId, $userId, $scope);

        $client->request('GET', '/oauth2/authorize', [
            'response_type' => 'code',
            'client_id' => $clientId,
            'redirect_uri' => RegularClientEnum::REDIRECT_URI,
            'scope' => implode(' ', array_merge($scope, [ScopeEnum::USER_PROFILE])),
            'state' => '1234abcd',
        ]);

        $this->assertResponseRedirects('http://localhost/login');
        $crawler = $client->followRedirect();

        $this->assertSelectorExists('form');

        $form = $crawler->selectButton('Sign in')->form(method: 'post');
        $form['_username'] = $userId;
        $form['_password'] = RegularUserEnum::PASSWORD;

        $client->submit($form);

        $this->assertResponseRedirects('http://localhost/oauth2/authorize?client_id=11111111111111111111111111111111&redirect_uri=https%3A%2F%2Fzion.social%2Fauth&response_type=code&scope=user.email%20user.profile&state=1234abcd');
        $client->followRedirect();

        $this->assertResponseRedirects('/oauth2/consent?client_id=11111111111111111111111111111111&redirect_uri=https://zion.social/auth&response_type=code&scope=user.email%20user.profile&state=1234abcd');
        $crawler = $client->followRedirect();

        $this->assertSelectorExists('form');
        $this->assertSelectorTextContains('form>p', 'already has access to your details:');

        $form = $crawler->selectButton('Grant access')->form(method: 'post');
        $form['consent'] = 'yes';
        $client->submit($form);

        $this->assertResponseRedirects('/oauth2/authorize?client_id=11111111111111111111111111111111&redirect_uri=https://zion.social/auth&response_type=code&scope=user.email%20user.profile&state=1234abcd');

        $userConsent = $this->getExistingUserConsent(
            RegularClientEnum::IDENTIFIER,
            RegularUserEnum::USERNAME
        );

        $this->assertNotEmpty($userConsent);

        $client->followRedirect();
        $crawler = $client->followRedirect();

        $expectedUriWithCode = 'https://zion.social/auth?code=@string@&state=1234abcd';
        $uriWithCode = $crawler->getUri();

        $this->assertMatchesPattern($expectedUriWithCode, $uriWithCode);

        preg_match('/https:\\/\\/zion\.social\\/auth\?code=([^&]+)/', $uriWithCode, $matches);
        $code = $matches[1] ?? null;

        $this->assertNotEmpty($code);

        $authCodePayload = [
            'grant_type' => 'authorization_code',
            'client_id' => RegularClientEnum::IDENTIFIER,
            'client_secret' => RegularClientEnum::SECRET,
            'redirect_uri' => RegularClientEnum::REDIRECT_URI,
            'code' => $code
        ];

        $expectedResponse = <<<TXT
{
    "token_type": "Bearer",
    "expires_in": 3600,
    "access_token": "@string@",
    "refresh_token": "@string@"
}
TXT;

        $client->jsonRequest('POST', '/api/oauth2/token', $authCodePayload);

        $this->assertResponseIsSuccessful();
        $this->assertMatchesPattern($expectedResponse, $client->getResponse()->getContent());
    }

    private function getExistingUserConsent(string $clientId, string $userId): ?Oauth2UserConsent
    {
        $userConsentDTO = new GetUserConsentDTO();
        $userConsentDTO->user = $userId;
        $userConsentDTO->client = $clientId;

        return $this->getContainer()->get(QueryBusInterface::class)
            ->handle(new GetOauth2UserConsentQuery($userConsentDTO));
    }

    private function getClientModel(string $clientId): ?Client
    {
        return $this->getContainer()->get(QueryBusInterface::class)
            ->handle(new GetOauth2ClientQuery($clientId));
    }

    private function getUserModel(string $userId): ?User
    {
        return $this->getContainer()->get(QueryBusInterface::class)
            ->handle(new GetUserQuery($userId));
    }

    private function createUserConsent(string $clientId, string $userId, array $scope): void
    {
        $consent = new OAuth2UserConsent();;
        $consent->setScopes($scope);
        $consent->setClient($this->getClientModel($clientId));
        $consent->setUser($this->getUserModel($userId));

        $this->getContainer()->get(CommandBusInterface::class)
            ->handle(new CreateOauth2UserConsentCommand($consent));
    }
}
