<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\UserModule\Application;

use Netborg\Fediverse\Api\Tests\AbstractApiTestCase;
use Netborg\Fediverse\Api\UserModule\Application\Repository\ActivationLinkRepositoryInterface;
use Netborg\Fediverse\Api\UserModule\Application\Repository\UserRepositoryInterface;
use Netborg\Fediverse\Api\UserModule\Domain\Model\ActivationLink;
use Netborg\Fediverse\Api\UserModule\Domain\Model\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Messenger\Transport\InMemoryTransport;
use Symfony\Component\Uid\Uuid;

class LinkActivationTest extends AbstractApiTestCase
{
    private KernelBrowser $client;
    private ActivationLinkRepositoryInterface $activationLinkRepository;
    private UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->activationLinkRepository = self::getContainer()->get(ActivationLinkRepositoryInterface::class);
        $this->userRepository = self::getContainer()->get(UserRepositoryInterface::class);

        parent::setUp();
    }

    public function testLinkNotFoundCase(): void
    {
        /** @var InMemoryTransport $eventBus */
        $eventBus = self::getContainer()->get('messenger.transport.events');

        $this->client->request('GET', '/activation/018655be-5d0d-7b3b-be3f-691582cc8e8f');

        $expected = <<<TXT
{
  "code": 0,
  "error": "Invalid activation link!"
}
TXT;
        $this->assertResponseStatusCodeSame(404);
        $this->assertMatchesPattern($expected, $this->client->getResponse()->getContent());
        $this->assertCount(0, $eventBus->getSent());
    }

    public function testInvalidLinkStructureCase(): void
    {
        /** @var InMemoryTransport $eventBus */
        $eventBus = self::getContainer()->get('messenger.transport.events');

        $this->client->request('GET', '/activation/12345678-1234-1234-1234-1234567890ab');

        $expected = <<<TXT
{
  "message": "Invalid data provided.",
  "errors": [
    {
      "property": "activationLink",
      "error": "Invalid activation link"
    }
  ]
}
TXT;
        $this->assertResponseStatusCodeSame(400);
        $this->assertMatchesPattern($expected, $this->client->getResponse()->getContent());
        $this->assertCount(0, $eventBus->getSent());
    }

    public function testExpiredActivationLinkCase(): void
    {
        /** @var InMemoryTransport $eventBus */
        $eventBus = self::getContainer()->get('messenger.transport.events');

        $this->userRepository->save(
            $user = (new User())
                ->setUsername('test')
                ->setEmail('test@example.com')
                ->setPassword('12345678')
        );

        $uuid = Uuid::v7()->toRfc4122();

        $this->activationLinkRepository->save(
            (new ActivationLink())
                ->setId(1)
                ->setUuid($uuid)
                ->setUser($user)
                ->setExpiresAt((new \DateTimeImmutable())
                   ->sub(new \DateInterval('P7D'))
                   ->format(\DateTimeInterface::RFC3339_EXTENDED)
                )
        );

        $this->client->request('GET', sprintf('/activation/%s', $uuid));

        $expected = <<<TXT
{
  "error": "Invalid activation link!",
  "code": 0
}
TXT;
        $this->assertResponseStatusCodeSame(404);
        $this->assertMatchesPattern($expected, $this->client->getResponse()->getContent());
        $this->assertCount(0, $eventBus->getSent());
    }

    public function testSuccessfulActivationCase(): void
    {
        /** @var InMemoryTransport $eventBus */
        $eventBus = self::getContainer()->get('messenger.transport.events');

        $this->userRepository->save(
            $user = (new User())
                ->setUsername('test')
                ->setEmail('test@example.com')
                ->setPassword('12345678')
        );

        $uuid = Uuid::v7()->toRfc4122();

        $this->activationLinkRepository->save(
            $activationLink = (new ActivationLink())
                ->setId(1)
                ->setUuid($uuid)
                ->setUser($user)
                ->setExpiresAt((new \DateTimeImmutable())
                   ->add(new \DateInterval('PT24H'))
                   ->format(\DateTimeInterface::RFC3339_EXTENDED)
                )
        );

        $this->client->request('GET', sprintf('/activation/%s', $uuid));

        $expected = <<<TXT
{
  "activation_status": true
}
TXT;
        $this->assertResponseIsSuccessful();
        $this->assertMatchesPattern($expected, $this->client->getResponse()->getContent());
        $this->assertTrue($this->userRepository->findOneById($user->getId())->isActive());
        $this->assertNull($this->activationLinkRepository->findOneByUuid($activationLink->getUuid()));
        $this->assertCount(1, $eventBus->getSent());
    }
}
