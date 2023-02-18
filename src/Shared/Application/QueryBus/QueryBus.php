<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Application\QueryBus;

use Netborg\Fediverse\Api\Shared\Domain\QueryBus\Query\QueryInterface;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryBusInterface;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryHandlerInterface;

class QueryBus implements QueryBusInterface
{
    /** @var QueryHandlerInterface[] */
    private array $queryHandlers = [];

    public function registerQueryHandler(QueryHandlerInterface $queryHandler): void
    {
        if (isset($this->queryHandlers[$queryHandler->getName()])) {
            $msg = sprintf(
                'Multiple query handlers exist using the same name `%s`',
                $queryHandler->getName()
            );
            throw new \LogicException($msg);
        }

        $this->queryHandlers[$queryHandler->getName()] = $queryHandler;
    }

    /** @return array<string,mixed>|mixed */
    public function handle(QueryInterface $query): mixed
    {
        $results = [];
        $handled = false;

        foreach ($this->queryHandlers as $name => $queryHandler) {
            if ($queryHandler->supports($query->getName(), $query->getSubjectType())) {
                $results[$name] = $queryHandler->handle($query);
                $handled = true;
            }
        }

        if (!$handled) {
            $msg = sprintf(
                'Unsupported query `%s` [%s]! Add QueryHandler for this query.',
                $query->getName(),
                $query->getSubjectType()
            );
            throw new \RuntimeException($msg);
        }

        return 1 === count($results)
            ? array_shift($results)
            : $results;
    }
}
