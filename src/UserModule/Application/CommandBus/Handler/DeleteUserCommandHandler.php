<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\CommandBus\Handler;

use Netborg\Fediverse\Api\Shared\Domain\CommandBus\Command\CommandInterface;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandHandlerInterface;
use Netborg\Fediverse\Api\UserModule\Application\CommandBus\Command\DeleteUserCommand;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Repository\UserRepositoryInterface;

class DeleteUserCommandHandler implements CommandHandlerInterface
{
    public const NAME = 'user.delete';
    private const STRING = 'string';
    private const INT = 'integer';

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function supports(string $command, string $subjectType): bool
    {
        return DeleteUserCommand::NAME === $command
            && in_array($subjectType, [
                User::class,
                self::STRING,
                self::INT,
            ]);
    }

    public function handle(CommandInterface $command): mixed
    {
        $user = match ($command->getSubjectType()) {
            User::class => $command->getSubject(),
            self::STRING => $this->userRepository->findOneByAnyIdentifier($command->getSubject()),
            self::INT => $this->userRepository->findOneById($command->getSubject()),
            default => null,
        };

        if (!$user) {
            return false;
        }

        $this->userRepository->remove($user);

        return true;
    }
}
