<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\CommandBus\Handler;

use Netborg\Fediverse\Api\Shared\Domain\CommandBus\Command\CommandInterface;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandHandlerInterface;
use Netborg\Fediverse\Api\UserModule\Application\CommandBus\Command\UpdateUserCommand;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Repository\UserRepositoryInterface;

class UpdateUserCommandHandler implements CommandHandlerInterface
{
    public const NAME = 'user.update';

    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function supports(string $command, string $subjectType): bool
    {
        return UpdateUserCommand::NAME === $command
            && User::class === $subjectType;
    }

    public function handle(CommandInterface $command): mixed
    {
        /** @var User $userEntity */
        $userEntity = $command->getSubject();

        $this->userRepository->save($userEntity, true);

        return $userEntity;
    }
}
