<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Domain\CommandBus\Command;

interface CommandInterface
{
    /**
     * Should return command name as string representation.
     */
    public function getName(): string;

    /**
     * Should return carried subject's type or class name if subject is and object.
     */
    public function getSubjectType(): string;

    /**
     * Should return carried data as array, DTO object or Domain Model.
     */
    public function getSubject(): mixed;
}
