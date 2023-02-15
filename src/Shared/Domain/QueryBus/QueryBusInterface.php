<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Domain\QueryBus;

use Netborg\Fediverse\Api\Shared\Domain\QueryBus\Query\QueryInterface;

interface QueryBusInterface
{
    public static function registerQueryHandler(QueryHandlerInterface $queryHandler): void;

    /** @return array<string,mixed>|mixed */
    public function handle(QueryInterface $query): mixed;
}
