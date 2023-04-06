<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\CommandBus\Command;

use Netborg\Fediverse\Api\Shared\Application\CommandBus\Command\AbstractCommand;

class CreatePersonCommand extends AbstractCommand
{
    public const NAME = 'command.person.create';

    protected string $name = self::NAME;
}
