<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Application\CommandBus;

use Netborg\Fediverse\Api\Shared\Domain\CommandBus\Command\CommandInterface;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandBusInterface;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandHandlerInterface;

class CommandBus implements CommandBusInterface
{
    /** @var CommandHandlerInterface[] */
    private static array $commandHandlers = [];

    public static function registerCommandHandler(CommandHandlerInterface $commandHandler): void
    {
        if (isset(self::$commandHandlers[$commandHandler->getName()])) {
            $msg = sprintf(
                'Multiple command handlers exist using the same name `%s`',
                $commandHandler->getName()
            );
            throw new \LogicException($msg);
        }

        self::$commandHandlers[$commandHandler->getName()] = $commandHandler;
    }

    /** @return array<string,mixed> */
    public function handle(CommandInterface $command): array
    {
        $results = [];

        foreach (self::$commandHandlers as $name => $commandHandler) {
            if ($commandHandler->supports($command->getName(), $command->getSubjectType())) {
                $results[$name] = $commandHandler->handle($command);
            }
        }

        return $results;
    }
}
