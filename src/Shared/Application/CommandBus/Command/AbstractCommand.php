<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Application\CommandBus\Command;

use Netborg\Fediverse\Api\Shared\Domain\CommandBus\Command\CommandInterface;

abstract class AbstractCommand implements CommandInterface
{
    protected string $name;

    protected string $subjectType = 'null';

    public function __construct(
        protected mixed $subject = null
    ) {
        $this->name = !empty($this->name) ? $this->name : get_class($this);
        $this->subjectType = $this->resolveSubjectType($this->subject);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSubjectType(): string
    {
        return $this->subjectType;
    }

    public function getSubject(): mixed
    {
        return $this->subject;
    }

    private function resolveSubjectType(mixed $subject): string
    {
        if (is_object($subject)) {
            return get_class($subject);
        }

        return gettype($subject);
    }
}
