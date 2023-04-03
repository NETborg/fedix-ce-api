<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests\ActivityPubModule\Unit\Domain\Service;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Dispatcher\EventDispatcherInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\Person\PersonPostCreate;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\Person\PersonPreCreate;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Exception\PersonAlreadyExistsException;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Exception\ValidationException;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Logger\LoggerInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Repository\PersonRepositoryInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Service\PersonCreatorService;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Tool\UuidGeneratorInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Validator\PersonValidatorInterface;
use Netborg\Fediverse\Api\Tests\AbstractUnitTestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

/** @covers \Netborg\Fediverse\Api\ActivityPubModule\Domain\Service\PersonCreatorService */
class PersonCreatorServiceTest extends AbstractUnitTestCase
{
    private ObjectProphecy $logger;
    private ObjectProphecy $validator;
    private ObjectProphecy $personRepository;
    private ObjectProphecy $eventDispatcher;
    private ObjectProphecy $uuidGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->validator = $this->prophesize(PersonValidatorInterface::class);
        $this->personRepository = $this->prophesize(PersonRepositoryInterface::class);
        $this->eventDispatcher= $this->prophesize(EventDispatcherInterface::class);
        $this->uuidGenerator = $this->prophesize(UuidGeneratorInterface::class);
    }

    public function testCreateForEmptyOwner(): void
    {
        $this->logger->debug(Argument::type('string'))->shouldBeCalledTimes(3);

        $this->expectException(ValidationException::class);

        $service = $this->createService();
        $service->createForOwner($this->prophesize(Person::class)->reveal(), '');
    }

    public function testCreateForOwnerAlreadyOwningPerson(): void
    {
        $this->logger->debug(Argument::type('string'))->shouldBeCalledTimes(4);
        $this->personRepository->hasPerson('owner')->willReturn(true);

        $person = $this->prophesize(Person::class);

        $this->expectException(PersonAlreadyExistsException::class);

        $service = $this->createService();
        $service->createForOwner($person->reveal(), 'owner');
    }

    public function testCreateForInvalidPersonData(): void
    {
        $this->logger->debug(Argument::type('string'))->shouldBeCalledTimes(5);
        $this->logger->debug(Argument::type('string'), Argument::type('array'))->shouldBeCalledOnce();
        $this->personRepository->hasPerson('owner')->willReturn(false);
        $this->uuidGenerator->generate()->shouldBeCalledOnce()->willReturn('uuid');

        $person = $this->prophesize(Person::class);
        $person->getId()->shouldBeCalledOnce()->willReturn(null);
        $person->setId('uuid')->shouldBeCalledOnce();
        $person->addOwner('owner')->shouldBeCalledOnce();
        $person->getPreferredUsername()->shouldBeCalled();

        $exception = new ValidationException();

        $this->validator
            ->validate(Argument::type(Person::class), ['action' => 'create'])
            ->shouldBeCalledOnce()
            ->willThrow($exception)
        ;

        $this->expectExceptionObject($exception);

        $service = $this->createService();
        $service->createForOwner($person->reveal(), 'owner');
    }

    public function testCreateForOwnerSuccess(): void
    {
        $this->logger->debug(Argument::type('string'))->shouldBeCalledTimes(13);
        $this->personRepository->hasPerson('owner')->willReturn(false);
        $this->uuidGenerator->generate()->shouldNotBeCalled();

        $person = $this->prophesize(Person::class);
        $person->getId()->shouldBeCalledOnce()->willReturn('uuid');
        $person->setId('uuid')->shouldBeCalledOnce();
        $person->addOwner('owner')->shouldBeCalledOnce();
        $person->getPreferredUsername()->shouldBeCalled();

        $this->validator
            ->validate(Argument::type(Person::class), ['action' => 'create'])
            ->shouldBeCalledOnce();

        $this->eventDispatcher->dispatch(Argument::type(PersonPreCreate::class))->shouldBeCalledOnce();
        $this->eventDispatcher->dispatch(Argument::type(PersonPostCreate::class))->shouldBeCalledOnce();
        $this->personRepository->create(Argument::type(Person::class))->shouldBeCalledOnce();

        $service = $this->createService();
        $service->createForOwner($person->reveal(), 'owner');
    }

    private function createService(): PersonCreatorService
    {
        return new PersonCreatorService(
            $this->logger->reveal(),
            $this->validator->reveal(),
            $this->personRepository->reveal(),
            $this->eventDispatcher->reveal(),
            $this->uuidGenerator->reveal()
        );
    }
}
