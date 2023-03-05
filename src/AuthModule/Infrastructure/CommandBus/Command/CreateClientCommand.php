<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\CommandBus\Command;

use Netborg\Fediverse\Api\Shared\Application\CommandBus\Command\AbstractCommand;

class CreateClientCommand extends AbstractCommand
{
    public const NAME = 'command.client.create';

    protected string $name = self::NAME;
}
