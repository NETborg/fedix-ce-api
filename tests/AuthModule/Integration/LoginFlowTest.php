<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\AuthModule\Integration;

use Netborg\Fediverse\Api\Tests\AbstractApiTestCase;
use Netborg\Fediverse\Api\Tests\UserModule\Enum\RegularUserEnum;

class LoginFlowTest extends AbstractApiTestCase
{
    public function testJSONInvalidPasswordCase(): void
    {
        $client = static::createClient();

        $expectedCsrfTokenResponse = <<<TXT
{
  "_csrf_token": "@string@"
}
TXT;

        $client->jsonRequest(
            method: 'GET',
            uri: '/login',
            server: [
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $output = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesPattern($expectedCsrfTokenResponse, $output);

        $csrfToken = json_decode($output)->_csrf_token;

        $payload = [
            '_username' => RegularUserEnum::EMAIL,
            '_password' => 'invalidPassword',
            '_csrf_token' => $csrfToken
        ];

        $crawler = $client->request(
            method: 'POST',
            uri: '/login',
            parameters: $payload
        );

        $this->assertResponseRedirects('http://localhost/login');
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame('http://localhost/login', $crawler->getUri());
        $this->assertSelectorExists('div.alert.alert-danger');
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Invalid credentials.');
        $this->assertInputValueSame('_username', RegularUserEnum::EMAIL);
        $this->assertInputValueSame('_password', '');
    }

    public function testFORMInvalidPasswordCase(): void
    {
        $client = static::createClient();

        $crawler = $client->request(
            method: 'GET',
            uri: '/login'
        );

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        $form = $crawler->selectButton('Sign in')->form(method: 'post');
        $form['_username'] = RegularUserEnum::EMAIL;
        $form['_password'] = 'invalidPassword';

        $crawler = $client->submit($form);

        $this->assertResponseRedirects('http://localhost/login');
        $client->followRedirect();

        $this->assertSame('http://localhost/login', $crawler->getUri());
        $this->assertSelectorExists('div.alert.alert-danger');
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Invalid credentials.');
        $this->assertInputValueSame('_username', RegularUserEnum::EMAIL);
        $this->assertInputValueSame('_password', '');
    }

    public function testJSONInvalidUsernameCase(): void
    {
        $client = static::createClient();

        $expectedCsrfTokenResponse = <<<TXT
{
  "_csrf_token": "@string@"
}
TXT;

        $client->jsonRequest(
            method: 'GET',
            uri: '/login',
            server: [
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $output = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesPattern($expectedCsrfTokenResponse, $output);

        $csrfToken = json_decode($output)->_csrf_token;

        $payload = [
            '_username' => 'NonExisting',
            '_password' => 'invalidPassword',
            '_csrf_token' => $csrfToken
        ];

        $crawler = $client->request(
            method: 'POST',
            uri: '/login',
            parameters: $payload
        );

        $this->assertResponseRedirects('http://localhost/login');
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame('http://localhost/login', $crawler->getUri());
        $this->assertSelectorExists('div.alert.alert-danger');
        $this->assertSelectorTextContains('div.alert.alert-danger', 'An authentication exception occurred.');
        $this->assertInputValueSame('_username', 'NonExisting');
        $this->assertInputValueSame('_password', '');
    }

    public function testFORMInvalidUsernameCase(): void
    {
        $client = static::createClient();

        $crawler = $client->request(
            method: 'GET',
            uri: '/login'
        );

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        $form = $crawler->selectButton('Sign in')->form(method: 'post');
        $form['_username'] = 'NonExisting';
        $form['_password'] = 'invalidPassword';

        $crawler = $client->submit($form);

        $this->assertResponseRedirects('http://localhost/login');
        $client->followRedirect();

        $this->assertSame('http://localhost/login', $crawler->getUri());
        $this->assertSelectorExists('div.alert.alert-danger');
        $this->assertSelectorTextContains('div.alert.alert-danger', 'An authentication exception occurred.');
        $this->assertInputValueSame('_username', 'NonExisting');
        $this->assertInputValueSame('_password', '');
    }

    public function testJSONSuccessfulLoginByEmailCase(): void
    {
        $client = static::createClient();

        $expectedCsrfTokenResponse = <<<TXT
{
  "_csrf_token": "@string@"
}
TXT;

        $client->jsonRequest(
            method: 'GET',
            uri: '/login',
            server: [
                'HTTP_ACCEPT' => 'application/json'
            ]
        );
        $output = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesPattern($expectedCsrfTokenResponse, $output);

        $csrfToken = json_decode($output)->_csrf_token;

        $payload = [
            '_username' => RegularUserEnum::EMAIL,
            '_password' => RegularUserEnum::PASSWORD,
            '_csrf_token' => $csrfToken
        ];

        $client->request(
            method: 'POST',
            uri: '/login',
            parameters: $payload
        );

        $this->assertResponseRedirects('http://localhost/');
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesPattern('{"test":"OK"}', $client->getResponse()->getContent());
    }

    public function testFORMSuccessfulLoginByEmailCase(): void
    {
        $client = static::createClient();

        $crawler = $client->request(
            method: 'GET',
            uri: '/login'
        );

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        $form = $crawler->selectButton('Sign in')->form(method: 'post');
        $form['_username'] = RegularUserEnum::EMAIL;
        $form['_password'] = RegularUserEnum::PASSWORD;

        $client->submit($form);

        $this->assertResponseRedirects('http://localhost/');
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesPattern('{"test":"OK"}', $client->getResponse()->getContent());
    }

    public function testJSONSuccessfulLoginByUsernameCase(): void
    {
        $client = static::createClient();

        $expectedCsrfTokenResponse = <<<TXT
{
  "_csrf_token": "@string@"
}
TXT;

        $client->jsonRequest(
            method: 'GET',
            uri: '/login',
            server: [
                        'HTTP_ACCEPT' => 'application/json'
                    ]
        );
        $output = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesPattern($expectedCsrfTokenResponse, $output);

        $csrfToken = json_decode($output)->_csrf_token;

        $payload = [
            '_username' => RegularUserEnum::USERNAME,
            '_password' => RegularUserEnum::PASSWORD,
            '_csrf_token' => $csrfToken
        ];

        $client->request(
            method: 'POST',
            uri: '/login',
            parameters: $payload
        );

        $this->assertResponseRedirects('http://localhost/');
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesPattern('{"test":"OK"}', $client->getResponse()->getContent());
    }

    public function testFORMSuccessfulLoginByUsernameCase(): void
    {
        $client = static::createClient();

        $crawler = $client->request(
            method: 'GET',
            uri: '/login'
        );

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        $form = $crawler->selectButton('Sign in')->form(method: 'post');
        $form['_username'] = RegularUserEnum::USERNAME;
        $form['_password'] = RegularUserEnum::PASSWORD;

        $client->submit($form);

        $this->assertResponseRedirects('http://localhost/');
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesPattern('{"test":"OK"}', $client->getResponse()->getContent());
    }
}
