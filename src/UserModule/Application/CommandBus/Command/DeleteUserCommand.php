<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\CommandBus\Command;

use Netborg\Fediverse\Api\Shared\Application\CommandBus\Command\AbstractCommand;

class DeleteUserCommand extends AbstractCommand
{
    public const NAME = 'command.user.delete';

    protected string $name = self::NAME;
}
