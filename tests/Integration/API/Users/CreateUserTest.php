<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\Integration\API\Users;

use Netborg\Fediverse\Api\Tests\Integration\API\AbstractApiTestCase;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Repository\UserRepository;

class CreateUserTest extends AbstractApiTestCase
{
    public function testErrorMissingEmail(): void
    {
        $client = static::createClient();
        $payload = [
            'firstName' => 'Test',
            'lastName' => 'User',
            'username' => 'TestUser',
            'password' => '12345678',
        ];

        $expected = <<<TXT
{
  "message": "Invalid data provided.",
  "errors": [
    {
      "property": "email",
      "error": "This value should not be blank."
    }
  ]
}
TXT;

        $crawler = $client->jsonRequest('POST', '/api/v1/user', $payload);
        $output = $client->getResponse()->getContent();

        $this->assertResponseStatusCodeSame(400);
        $this->assertMatchesPattern($expected, $output);
    }

    public function testErrorMissingUsername(): void
    {
        $client = static::createClient();
        $payload = [
            'firstName' => 'Test',
            'lastName' => 'User',
            'email' => 'test@example.com',
            'password' => '12345678',
        ];

        $expected = <<<TXT
{
  "message": "Invalid data provided.",
  "errors": [
    {
      "property": "username",
      "error": "This value should not be blank."
    }
  ]
}
TXT;

        $crawler = $client->jsonRequest('POST', '/api/v1/user', $payload);
        $output = $client->getResponse()->getContent();

        $this->assertResponseStatusCodeSame(400);
        $this->assertMatchesPattern($expected, $output);
    }

    public function testErrorInvalidPassword(): void
    {
        $client = static::createClient();
        $payload = [
            'firstName' => 'Test',
            'lastName' => 'User',
            'username' => 'TestUser',
            'email' => 'test@example.com',
            'password' => '12345',
        ];

        $expected = <<<TXT
{
  "message": "Invalid data provided.",
  "errors": [
    {
      "property": "password",
      "error": "Password must have min. 8 characters"
    }
  ]
}
TXT;

        $crawler = $client->jsonRequest('POST', '/api/v1/user', $payload);
        $output = $client->getResponse()->getContent();

        $this->assertResponseStatusCodeSame(400);
        $this->assertMatchesPattern($expected, $output);
    }

    public function testCreateUserFeature(): void
    {
        $client = static::createClient();
        $payload = [
            'firstName' => 'Test',
            'lastName' => 'User',
            'username' => 'TestUser',
            'email' => 'test@example.com',
            'password' => '12345678',
        ];

        $expected = <<<TXT
{
  "user": {
    "uuid": "@uuid@",
    "firstName": "Test",
    "lastName": "User",
    "email": "test@example.com",
    "username": "TestUser"
  },
  "activationLinkSent": true
}
TXT;

        $crawler = $client->jsonRequest('POST', '/api/v1/user', $payload);
        $output = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesPattern($expected, $output);
    }

    public function testPreventToRegisterDuplicateAccounts(): void
    {
        $client = static::createClient();
        $payload = [
            'firstName' => 'Test',
            'lastName' => 'User',
            'username' => 'TestUser',
            'email' => 'test@example.com',
            'password' => '12345678',
        ];

        $repository = self::getContainer()->get(UserRepository::class);
        $repository->save(
            (new User())
                ->setFirstName('Test')
                ->setLastName('User')
                ->setUsername('TestUser')
                ->setEmail('test@example.com')
                ->setPassword('12345678'),
            true
        );

        $expected = <<<TXT
{
  "message": "Invalid data provided.",
  "errors": [
    {
      "property": "email",
      "error": "This value is already used."
    },
    {
      "property": "username",
      "error": "This value is already used."
    }
  ]
}
TXT;

        $crawler = $client->jsonRequest('POST', '/api/v1/user', $payload);
        $output = $client->getResponse()->getContent();

        $this->assertResponseStatusCodeSame(400);
        $this->assertMatchesPattern($expected, $output);
    }
}
