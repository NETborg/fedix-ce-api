<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\QueryBus\Handler;

use Netborg\Fediverse\Api\Shared\Domain\QueryBus\Query\QueryInterface;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryHandlerInterface;
use Netborg\Fediverse\Api\UserModule\Application\QueryBus\Query\GetUserQuery;
use Netborg\Fediverse\Api\UserModule\Application\Repository\UserRepositoryInterface;

class GetUserQueryHandler implements QueryHandlerInterface
{
    public const NAME = 'user.get';
    private const STRING = 'string';
    private const INTEGER = 'integer';

    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function supports(string $query, string $subjectType): bool
    {
        return GetUserQuery::NAME === $query
            && in_array($subjectType, [
                self::INTEGER,
                self::STRING,
            ]);
    }

    public function handle(QueryInterface $query): mixed
    {
        return match ($query->getSubjectType()) {
            self::STRING, self::INTEGER => $this->userRepository->findOneByAnyIdentifier($query->getSubject()),
            default => null,
        };
    }
}
