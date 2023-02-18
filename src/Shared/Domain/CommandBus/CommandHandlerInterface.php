<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Domain\CommandBus;

use Netborg\Fediverse\Api\Shared\Domain\CommandBus\Command\CommandInterface;

interface CommandHandlerInterface
{
    public function getName(): string;

    public function supports(string $command, string $subjectType): bool;

    public function handle(CommandInterface $command): mixed;
}
