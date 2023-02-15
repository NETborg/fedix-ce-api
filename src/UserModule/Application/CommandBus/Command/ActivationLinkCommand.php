<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\CommandBus\Command;

use Netborg\Fediverse\Api\Shared\Application\CommandBus\Command\AbstractCommand;

class ActivationLinkCommand extends AbstractCommand
{
    public const NAME = 'command.activation_link.activate';

    protected string $name = self::NAME;
}
