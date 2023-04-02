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
    public function testUnauthorizedAccessOnlyPublicView(): void
    {
        $client = static::createClient();
        $preferredUsername = 'RegularPerson';

        $expected = <<<TXT
{
    "content": "<div>Some Regular Person text content</div>",
    "contentMap": {
        "en": "<div>Some Regular Person text content</div>",
        "pl_PL": "<div>Jakaś teksotwa zawartość Normalnej Osoby</div>"
    },
    "id": "http://localhost/api/v1/activity_pub/person/~RegularPerson",
    "image": "https://fedx.social/image/person/~RegularPerson",
    "inbox": "http://localhost/api/v1/activity_pub/person/~RegularPerson/inbox",
    "name": "Regular Person",
    "nameMap": {
        "en": "Regular Person",
        "pl_PL": "Normalna Osoba"
    },
    "outbox": "http://localhost/api/v1/activity_pub/person/~RegularPerson/outbox",
    "preferredUsername": "RegularPerson",
    "summary": "I'm just Regular Person summary",
    "summaryMap": {
        "en": "I'm just Regular Person summary",
        "pl_PL": "Jestem tylko Normalną Osobą"
    },
    "type": "Person",
    "url": "https://fedx.social/person/~RegularPerson"
}
TXT;

        $client->jsonRequest(
            method: 'GET',
            uri: '/api/v1/activity_pub/person/~'.$preferredUsername,
        );
        $output = $client->getResponse()->getContent();

        $this->assertResponseStatusCodeSame(200);
        $this->assertMatchesPattern($expected, $output);
    }

    public function testPublicViewForForeignUser(): void
    {
        $client = static::createClient();
        $preferredUsername = 'RegularPerson';

        $userUuid = $this->getFaker()->uuid();
        $user = $this->createUser($userUuid);

        $expected = <<<TXT
{
    "content": "<div>Some Regular Person text content</div>",
    "contentMap": {
        "en": "<div>Some Regular Person text content</div>",
        "pl_PL": "<div>Jakaś teksotwa zawartość Normalnej Osoby</div>"
    },
    "id": "http://localhost/api/v1/activity_pub/person/~RegularPerson",
    "image": "https://fedx.social/image/person/~RegularPerson",
    "inbox": "http://localhost/api/v1/activity_pub/person/~RegularPerson/inbox",
    "name": "Regular Person",
    "nameMap": {
        "en": "Regular Person",
        "pl_PL": "Normalna Osoba"
    },
    "outbox": "http://localhost/api/v1/activity_pub/person/~RegularPerson/outbox",
    "preferredUsername": "RegularPerson",
    "summary": "I'm just Regular Person summary",
    "summaryMap": {
        "en": "I'm just Regular Person summary",
        "pl_PL": "Jestem tylko Normalną Osobą"
    },
    "type": "Person",
    "url": "https://fedx.social/person/~RegularPerson"
}
TXT;

        $client->jsonRequest(
            method: 'GET',
            uri: '/api/v1/activity_pub/person/~'.$preferredUsername,
            server: [
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->createAccessToken(
                    $this->createRegularClient(),
                    $user->getUuid()
                )),
            ]
        );
        $output = $client->getResponse()->getContent();

        $this->assertResponseStatusCodeSame(200);
        $this->assertMatchesPattern($expected, $output);
    }

    public function testOwnerViewForOwningUser(): void
    {
        $client = static::createClient();
        $preferredUsername = 'RegularPerson';

        $expected = <<<TXT
{
    "content": "<div>Some Regular Person text content</div>",
    "contentMap": {
        "en": "<div>Some Regular Person text content</div>",
        "pl_PL": "<div>Jakaś teksotwa zawartość Normalnej Osoby</div>"
    },
    "id": "http://localhost/api/v1/activity_pub/person/~RegularPerson",
    "image": "https://fedx.social/image/person/~RegularPerson",
    "inbox": "http://localhost/api/v1/activity_pub/person/~RegularPerson/inbox",
    "name": "Regular Person",
    "nameMap": {
        "en": "Regular Person",
        "pl_PL": "Normalna Osoba"
    },
    "outbox": "http://localhost/api/v1/activity_pub/person/~RegularPerson/outbox",
    "preferredUsername": "RegularPerson",
    "summary": "I'm just Regular Person summary",
    "summaryMap": {
        "en": "I'm just Regular Person summary",
        "pl_PL": "Jestem tylko Normalną Osobą"
    },
    "type": "Person",
    "url": "https://fedx.social/person/~RegularPerson",
    "owners": [
        "@uuid@"
    ]
}
TXT;

        $client->jsonRequest(
            method: 'GET',
            uri: '/api/v1/activity_pub/person/~'.$preferredUsername,
            server: [
                'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $this->createAccessToken(
                    $this->createRegularClient(),
                   RegularUserEnum::UUID
                )),
            ]
        );
        $output = $client->getResponse()->getContent();

        $this->assertResponseStatusCodeSame(200);
        $this->assertMatchesPattern($expected, $output);
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
