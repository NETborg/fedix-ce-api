<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Domain\CommandBus;

use Netborg\Fediverse\Api\Shared\Domain\CommandBus\Command\CommandInterface;

interface CommandBusInterface
{
    public static function registerCommandHandler(CommandHandlerInterface $commandHandler): void;

    /** @return array<string,mixed>|mixed */
    public function handle(CommandInterface $command): mixed;
}
