<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\ActivityPubModule\Application;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Repository\PersonRepositoryInterface;
use Netborg\Fediverse\Api\Tests\AbstractApiTestCase;
use Netborg\Fediverse\Api\Tests\UserModule\Enum\RegularUserEnum;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Repository\UserRepository;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class GetPersonTest extends AbstractApiTestCase
{
    public function testUnauthorizedAccessDenied(): void
    {
        $client = static::createClient();
        /** @var PersonRepositoryInterface $repository */
        $repository = self::getContainer()->get(PersonRepositoryInterface::class);

        $payload = [
            'name' => 'Test Person',
            'preferredUsername' => 'TestPerson',
            'summary' => 'Just a Test Person summary',
        ];

        $expected = '';

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/v1/activity_pub/person',
            parameters: $payload
        );
        $output = $client->getResponse()->getContent();

        $this->assertResponseStatusCodeSame(401);
        $this->assertMatchesPattern($expected, $output);
        $this->assertNull($repository->findOneByPreferredUsername('TestPerson'));
    }

    public function testMissingRequiredAttributes(): void
    {
        $client = static::createClient();
        /** @var PersonRepositoryInterface $repository */
        $repository = self::getContainer()->get(PersonRepositoryInterface::class);

        $userUuid = $this->getFaker()->uuid();
        $user = $this->createUser($userUuid);

        $payload = [
            'name' => 'Test Person',
            'preferredUsername' => '',
            'summary' => 'Just a Test Person summary',
        ];

        $expected = <<<TXT
{
    "code": 4000101,
    "error": "Invalid data provided!",
    "violations": {
        "preferredUsername": "@string@"
    }
}
TXT;

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/v1/activity_pub/person',
            parameters: $payload,
            server: [
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->createAccessToken(
                    $this->createRegularClient(),
                    $user->getUuid()
                ))
            ]
        );
        $output = $client->getResponse()->getContent();

        $this->assertResponseStatusCodeSame(400);
        $this->assertMatchesPattern($expected, $output);
        $this->assertNull($repository->findOneByPreferredUsername('TestPerson'));
    }

    public function testPreventMoreThenOnePersonAccount(): void
    {
        $client = static::createClient();
        /** @var PersonRepositoryInterface $repository */
        $repository = self::getContainer()->get(PersonRepositoryInterface::class);

        $payload = [
            'name' => 'Test Person',
            'preferredUsername' => '',
            'summary' => 'Just a Test Person summary',
        ];

        $expected = <<<TXT
{
    "code": 4030101,
    "error": "Person already exists for this User."
}
TXT;

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/v1/activity_pub/person',
            parameters: $payload,
            server: [
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->createAccessToken(
                    $this->createRegularClient(),
                    RegularUserEnum::UUID
                ))
            ]
        );
        $output = $client->getResponse()->getContent();

        $this->assertResponseStatusCodeSame(403);
        $this->assertMatchesPattern($expected, $output);
        $this->assertNull($repository->findOneByPreferredUsername('TestPerson'));
    }

    public function testSuccessfulPersonCreation(): void
    {
        $client = static::createClient();
        /** @var PersonRepositoryInterface $repository */
        $repository = self::getContainer()->get(PersonRepositoryInterface::class);

        $userUuid = $this->getFaker()->uuid();
        $user = $this->createUser($userUuid);

        $payload = [
            'name' => 'Test Person',
            'preferredUsername' => 'TestPerson',
            'summary' => 'Just a Test Person summary',
        ];

        $expected = <<<TXT
{
    "id": "@uuid@",
    "name": "Test Person",
    "preferredUsername": "TestPerson",
    "summary": "Just a Test Person summary"
}
TXT;

        $client->jsonRequest(
            method: 'POST',
            uri: '/api/v1/activity_pub/person',
            parameters: $payload,
            server: [
                        'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->createAccessToken(
                            $this->createRegularClient(),
                            $user->getUuid()
                        ))
                    ]
        );
        $output = $client->getResponse()->getContent();
        $dbRecord = $repository->findOneByPreferredUsername('TestPerson');

        $this->assertResponseStatusCodeSame(200);
        $this->assertMatchesPattern($expected, $output);
        $this->assertNotNull($dbRecord);
        $this->assertRecordContains($payload, $dbRecord);
    }

    private function createUser(string $uuid = null, string $username = null): User
    {
        $userRepository = self::getContainer()->get(UserRepository::class);

        $username ??= $this->getFaker()->userName();
        $user = (new User())
            ->setUuid($uuid ?? $this->getFaker()->uuid())
            ->setFirstName($this->getFaker()->firstName())
            ->setLastName($this->getFaker()->lastName())
            ->setUsername($username)
            ->setEmail($this->getFaker()->email())
            ->setPassword($this->getFaker()->password())
            ->setActive(true)
        ;

        $userRepository->save($user, true);

        return $user;
    }

    private function assertRecordContains(array $data, Person $person): void
    {
        $serializer = self::getContainer()->get('serializer');

        $normalized = $serializer->normalize(
            data: $person,
            context: [AbstractNormalizer::GROUPS => ['get']]
        );

        foreach ($data as $key => $value) {
            $this->assertSame($value, $normalized[$key]);
        }
    }
}
