<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\CommandBus\Command;

use Netborg\Fediverse\Api\Shared\Application\CommandBus\Command\AbstractCommand;

class UpdateActivationLinkCommand extends AbstractCommand
{
    public const NAME = 'command.activation_link.update';

    protected string $name = self::NAME;
}
