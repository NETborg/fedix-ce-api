<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\CommandBus\Handler;

use Netborg\Fediverse\Api\ActivityPubModule\Application\CommandBus\Command\CreatePersonCommand;
use Netborg\Fediverse\Api\ActivityPubModule\Application\Model\DTO\CreatePersonDTO;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Exception\PersonAlreadyExistsException;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Exception\ValidationException;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Service\PersonCreatorService;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\Command\CommandInterface;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandHandlerInterface;

class CreatePersonCommandHandler implements CommandHandlerInterface
{
    public const NAME = 'person.create';

    public function __construct(
        private readonly PersonCreatorService $personCreatorService,
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function supports(string $command, string $subjectType): bool
    {
        return CreatePersonCommand::NAME === $command && CreatePersonDTO::class === $subjectType;
    }

    /**
     * @throws ValidationException|PersonAlreadyExistsException
     */
    public function handle(CommandInterface $command): mixed
    {
        /** @var CreatePersonDTO $dto */
        $dto = $command->getSubject();

        /** @var Person $person */
        $person = $dto->person;

        $this->personCreatorService->createForOwner($person, $dto->userIdentifier);

        return true;
    }
}
