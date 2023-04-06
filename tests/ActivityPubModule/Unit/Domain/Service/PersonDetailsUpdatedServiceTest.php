<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\ActivityPubModule\Unit\Domain\Service;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Builder\ChangedFieldsCollectionBuilderInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Dispatcher\EventDispatcherInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Exception\UnauthorizedException;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Logger\LoggerInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\DTO\UpdatePersonDetailsDTO;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Repository\PersonRepositoryInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Service\PersonDetailsUpdaterService;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Validator\PersonValidatorInterface;
use Netborg\Fediverse\Api\Tests\AbstractUnitTestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class PersonDetailsUpdatedServiceTest extends AbstractUnitTestCase
{
    private ObjectProphecy $logger;
    private ObjectProphecy $validator;
    private ObjectProphecy $personRepository;
    private ObjectProphecy $eventDispatcher;
    private ObjectProphecy $changedFieldsCollectionBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->validator = $this->prophesize(PersonValidatorInterface::class);
        $this->personRepository = $this->prophesize(PersonRepositoryInterface::class);
        $this->eventDispatcher= $this->prophesize(EventDispatcherInterface::class);
        $this->changedFieldsCollectionBuilder = $this->prophesize(ChangedFieldsCollectionBuilderInterface::class);
    }

    public function testUpdateDetailsWithNullPerson(): void
    {
        $dto = $this->prophesize(UpdatePersonDetailsDTO::class);
        $dto->person = null;

        $this->expectException(\RuntimeException::class);

        $service= $this->createService();
        $service->updateDetails($dto->reveal());
    }

    public function testUpdateDetailsWithEmptyOwner(): void
    {
        $person = $this->prophesize(Person::class);
        $person->getPreferredUsername()->shouldBeCalled();

        $dto = $this->prophesize(UpdatePersonDetailsDTO::class);
        $dto->person = $person->reveal();
        $dto->owner = '';

        $this->logger->debug(Argument::type('string'))->shouldBeCalledTimes(2);

        $this->expectException(UnauthorizedException::class);

        $service= $this->createService();
        $service->updateDetails($dto->reveal());
    }

    public function testUpdateDetailsWithForeignOwner(): void
    {
        $person = $this->prophesize(Person::class);
        $person->getPreferredUsername()->shouldBeCalled();
        $person->hasOwner('owner')->shouldBeCalledOnce()->willReturn(false);

        $dto = $this->prophesize(UpdatePersonDetailsDTO::class);
        $dto->person = $person->reveal();
        $dto->owner = 'owner';

        $this->logger->debug(Argument::type('string'))->shouldBeCalledTimes(2);

        $this->expectException(UnauthorizedException::class);

        $service= $this->createService();
        $service->updateDetails($dto->reveal());
    }

    private function createService(): PersonDetailsUpdaterService
    {
        return new PersonDetailsUpdaterService(
            $this->logger->reveal(),
            $this->validator->reveal(),
            $this->personRepository->reveal(),
            $this->eventDispatcher->reveal(),
            $this->changedFieldsCollectionBuilder->reveal()
        );
    }
}
