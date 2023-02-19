<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\CommandBus\Handler;

use Netborg\Fediverse\Api\Shared\Domain\CommandBus\Command\CommandInterface;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandHandlerInterface;
use Netborg\Fediverse\Api\UserModule\Application\CommandBus\Command\DeleteActivationLinkCommand;
use Netborg\Fediverse\Api\UserModule\Application\Repository\ActivationLinkRepositoryInterface;
use Netborg\Fediverse\Api\UserModule\Domain\Model\ActivationLink;

class DeleteActivationLinkCommandHandler implements CommandHandlerInterface
{
    public const NAME = 'activation_link.delete';
    private const STRING = 'string';
    private const INT = 'integer';

    public function __construct(
        private readonly ActivationLinkRepositoryInterface $activationLinkRepository
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function supports(string $command, string $subjectType): bool
    {
        return DeleteActivationLinkCommand::NAME === $command
            && in_array($subjectType, [
                ActivationLink::class,
                self::STRING,
                self::INT,
            ]);
    }

    public function handle(CommandInterface $command): mixed
    {
        /** @var ActivationLink|null $activationLinkEntity */
        $activationLinkEntity = match ($command->getSubjectType()) {
            ActivationLink::class => $command->getSubject(),
            self::STRING => $this->activationLinkRepository->findOneByUuid($command->getSubject()),
            self::INT => $this->activationLinkRepository->findOneById($command->getSubject()),
            default => null,
        };

        if (!$activationLinkEntity) {
            return false;
        }

        $this->activationLinkRepository->remove($activationLinkEntity);

        return true;
    }
}
