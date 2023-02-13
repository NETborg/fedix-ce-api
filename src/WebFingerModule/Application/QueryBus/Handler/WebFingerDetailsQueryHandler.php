<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Application\QueryBus\Handler;

use Netborg\Fediverse\Api\Shared\Application\QueryBus\Query\GetWebFingerDetailsQuery;
use Netborg\Fediverse\Api\Shared\Domain\Model\DTO\GetWebFingerDetailsDTO;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\Query\QueryInterface;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryHandlerInterface;
use Netborg\Fediverse\Api\WebFingerModule\Application\Service\WebFingerServiceInterface;

class WebFingerDetailsQueryHandler implements QueryHandlerInterface
{
    public const NAME = 'webfinger.details';

    public function __construct(
        private readonly WebFingerServiceInterface $webFingerService,
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function supports(string $query, string $subjectType): bool
    {
        return GetWebFingerDetailsQuery::NAME === $query
            && GetWebFingerDetailsDTO::class === $subjectType;
    }

    public function handle(QueryInterface $query): mixed
    {
        /** @var GetWebFingerDetailsDTO $subject */
        $subject = $query->getSubject();

        return $this->webFingerService->resolve($subject->resource, $subject->rel);
    }
}
