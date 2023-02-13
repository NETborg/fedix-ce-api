<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Application\CommandBus\Command;

class RegisterUserCommand extends AbstractCommand
{
    public const NAME = 'command.user.register';

    protected string $name = self::NAME;
}
