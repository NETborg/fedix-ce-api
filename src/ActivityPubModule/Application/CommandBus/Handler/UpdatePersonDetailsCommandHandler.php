<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\CommandBus\Handler;

use Netborg\Fediverse\Api\ActivityPubModule\Application\CommandBus\Command\UpdatePersonDetailsCommand;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\DTO\UpdatePersonDetailsDTO;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Service\PersonDetailsUpdaterService;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\Command\CommandInterface;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandHandlerInterface;

class UpdatePersonDetailsCommandHandler implements CommandHandlerInterface
{
    private const NAME = 'person.update_details';

    public function __construct(
        private readonly PersonDetailsUpdaterService $personDetailsUpdaterService,
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function supports(string $command, string $subjectType): bool
    {
        return UpdatePersonDetailsCommand::NAME === $command && UpdatePersonDetailsDTO::class === $subjectType;
    }

    public function handle(CommandInterface $command): mixed
    {
        /** @var UpdatePersonDetailsDTO $dto */
        $dto = $command->getSubject();

        $this->personDetailsUpdaterService->updateDetails($dto);

        return true;
    }
}
