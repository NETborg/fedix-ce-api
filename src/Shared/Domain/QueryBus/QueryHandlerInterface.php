<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Domain\QueryBus;

use Netborg\Fediverse\Api\Shared\Domain\QueryBus\Query\QueryInterface;

interface QueryHandlerInterface
{
    public function getName(): string;

    public function supports(string $query, string $subjectType): bool;

    public function handle(QueryInterface $query): mixed;
}
