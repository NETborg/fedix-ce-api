<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\UserModule\Application;

use Netborg\Fediverse\Api\Tests\AbstractApiTestCase;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Repository\UserRepository;
use Symfony\Component\Messenger\Transport\InMemoryTransport;

class CreateUserTest extends AbstractApiTestCase
{
    public function testUnauthorizedAccessDenied(): void
    {
        $client = static::createClient();
        /** @var UserRepository $repository */
        $repository = self::getContainer()->get(UserRepository::class);
        /** @var InMemoryTransport $transport */
        $transport = self::getContainer()->get('messenger.transport.emails_activation');

        $payload = [
            'firstName' => 'Test',
            'lastName' => 'User',
            'username' => 'TestUser',
            'email' => 'test@example.com',
            'password' => '12345678',
        ];

        $expected = '';

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/v1/user',
            parameters: $payload
        );
        $output = $client->getResponse()->getContent();

        $this->assertResponseStatusCodeSame(401);
        $this->assertMatchesPattern($expected, $output);
        $this->assertCount(0, $repository->findBy(['username' => 'TestUser']));
        $this->assertCount(0, $transport->getSent());
    }

    public function testErrorMissingEmail(): void
    {
        $client = static::createClient();
        /** @var UserRepository $repository */
        $repository = self::getContainer()->get(UserRepository::class);
        /** @var InMemoryTransport $transport */
        $transport = self::getContainer()->get('messenger.transport.emails_activation');

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

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/v1/user',
            parameters: $payload,
            server: [
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->createAccessToken($this->createRegularClient())),
            ]
        );
        $output = $client->getResponse()->getContent();

        $this->assertResponseStatusCodeSame(400);
        $this->assertMatchesPattern($expected, $output);
        $this->assertCount(0, $repository->findBy(['username' => 'TestUser']));
        $this->assertCount(0, $transport->getSent());
    }

    public function testErrorMissingUsername(): void
    {
        $client = static::createClient();
        /** @var UserRepository $repository */
        $repository = self::getContainer()->get(UserRepository::class);
        /** @var InMemoryTransport $transport */
        $transport = self::getContainer()->get('messenger.transport.emails_activation');

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

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/v1/user',
            parameters: $payload,
            server: [
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->createAccessToken($this->createRegularClient())),
            ]
        );
        $output = $client->getResponse()->getContent();

        $this->assertResponseStatusCodeSame(400);
        $this->assertMatchesPattern($expected, $output);
        $this->assertCount(0, $repository->findBy(['username' => 'TestUser']));
        $this->assertCount(0, $transport->getSent());
    }

    public function testErrorInvalidPassword(): void
    {
        $client = static::createClient();
        /** @var UserRepository $repository */
        $repository = self::getContainer()->get(UserRepository::class);
        /** @var InMemoryTransport $transport */
        $transport = self::getContainer()->get('messenger.transport.emails_activation');

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

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/v1/user',
            parameters: $payload,
            server: [
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->createAccessToken($this->createRegularClient())),
            ]
        );
        $output = $client->getResponse()->getContent();

        $this->assertResponseStatusCodeSame(400);
        $this->assertMatchesPattern($expected, $output);
        $this->assertCount(0, $repository->findBy(['username' => 'TestUser']));
        $this->assertCount(0, $transport->getSent());
    }

    public function testCreateUserFeature(): void
    {
        $client = static::createClient();
        /** @var UserRepository $repository */
        $repository = self::getContainer()->get(UserRepository::class);
        /** @var InMemoryTransport $transport */
        $transport = self::getContainer()->get('messenger.transport.emails_activation');
        /** @var InMemoryTransport $eventBus */
        $eventBus = self::getContainer()->get('messenger.transport.events');

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
    "name": "Test User",
    "email": "test@example.com",
    "username": "TestUser",
    "createdAt": "@string@"
  },
  "activationLinkSent": true
}
TXT;

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/v1/user',
            parameters: $payload,
            server: [
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->createAccessToken($this->createRegularClient())),
            ]
        );

        $output = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertMatchesPattern($expected, $output);
        $this->assertCount(1, $repository->findBy(['username' => 'TestUser']));
        $this->assertCount(1, $transport->getSent());
        $this->assertCount(1, $eventBus->getSent());
    }

    public function testPreventToRegisterDuplicateAccounts(): void
    {
        $client = static::createClient();
        /** @var InMemoryTransport $transport */
        $transport = self::getContainer()->get('messenger.transport.emails_activation');
        /** @var InMemoryTransport $eventBus */
        $eventBus = self::getContainer()->get('messenger.transport.events');

        $payload = [
            'firstName' => 'Test',
            'lastName' => 'User',
            'username' => 'TestUser',
            'email' => 'test@example.com',
            'password' => '12345678',
        ];

        /** @var UserRepository $repository */
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

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/v1/user',
            parameters: $payload,
            server: [
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->createAccessToken($this->createRegularClient())),
            ]
        );
        $output = $client->getResponse()->getContent();

        $this->assertResponseStatusCodeSame(400);
        $this->assertMatchesPattern($expected, $output);
        $this->assertCount(1, $repository->findBy(['username' => 'TestUser']));
        $this->assertCount(0, $transport->getSent());
        $this->assertCount(0, $eventBus->getSent());
    }
}
