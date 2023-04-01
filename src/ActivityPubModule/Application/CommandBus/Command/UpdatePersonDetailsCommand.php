<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\CommandBus\Command;

use Netborg\Fediverse\Api\Shared\Application\CommandBus\Command\AbstractCommand;

class UpdatePersonDetailsCommand extends AbstractCommand
{
    public const NAME = 'command.person.update_details';

    protected string $name = self::NAME;
}
