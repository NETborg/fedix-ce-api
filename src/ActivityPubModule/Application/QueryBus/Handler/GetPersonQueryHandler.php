<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\QueryBus\Handler;

use Netborg\Fediverse\Api\ActivityPubModule\Application\QueryBus\Query\GetPersonQuery;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Repository\PersonRepositoryInterface;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\Query\QueryInterface;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryHandlerInterface;
use Symfony\Component\Uid\Uuid;

class GetPersonQueryHandler implements QueryHandlerInterface
{
    public const NAME = 'person.get';

    public function __construct(
        private readonly PersonRepositoryInterface $personRepository,
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
        $identifier = $query->getSubject();

        if (Uuid::isValid($identifier)) {
            return $this->personRepository->find($identifier);
        }

        return $this->personRepository->findOneByPreferredUsername($identifier);
    }
}
