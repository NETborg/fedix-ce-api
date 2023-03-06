<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Application\CommandBus\Command;

use Netborg\Fediverse\Api\Shared\Application\CommandBus\Command\AbstractCommand;

class CreateOauth2UserConsentCommand extends AbstractCommand
{
    public const NAME = 'command.oauth2_consent.create';

    protected string $name = self::NAME;
}
