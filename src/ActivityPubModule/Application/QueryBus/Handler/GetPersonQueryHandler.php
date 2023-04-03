<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\QueryBus\Handler;

use Netborg\Fediverse\Api\ActivityPubModule\Application\QueryBus\Query\GetPersonQuery;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Service\PersonGetterService;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\Query\QueryInterface;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryHandlerInterface;

class GetPersonQueryHandler implements QueryHandlerInterface
{
    public const NAME = 'person.get';

    public function __construct(
        private readonly PersonGetterService $personGetterService,
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function supports(string $query, string $subjectType): bool
    {
        return GetPersonQuery::NAME === $query && 'string' === $subjectType;
    }

    public function handle(QueryInterface $query): mixed
    {
        return $this->personGetterService->get($query->getSubject());
    }
}
