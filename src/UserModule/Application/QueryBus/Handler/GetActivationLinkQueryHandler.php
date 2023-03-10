<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\QueryBus\Handler;

use Netborg\Fediverse\Api\Shared\Domain\Model\DTO\ActivateLinkDTO;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\Query\QueryInterface;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryHandlerInterface;
use Netborg\Fediverse\Api\UserModule\Application\QueryBus\Query\GetActivationLinkQuery;
use Netborg\Fediverse\Api\UserModule\Application\Repository\ActivationLinkRepositoryInterface;

class GetActivationLinkQueryHandler implements QueryHandlerInterface
{
    public const NAME = 'activation_link.get';
    private const INTEGER = 'integer';
    private const STRING = 'string';

    public function __construct(
        private readonly ActivationLinkRepositoryInterface $activationLinkRepository
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function supports(string $query, string $subjectType): bool
    {
        return GetActivationLinkQuery::NAME === $query
            && in_array($subjectType, [
                ActivateLinkDTO::class,
                self::INTEGER,
                self::STRING,
            ]);
    }

    public function handle(QueryInterface $query): mixed
    {
        /** @var ActivateLinkDTO|int|string $activateLinkDTO */
        $activateLinkDTO = $query->getSubject();

        return match ($query->getSubjectType()) {
            ActivateLinkDTO::class => $this->activationLinkRepository->findOneByUuid($activateLinkDTO->activationLink),
            self::INTEGER => $this->activationLinkRepository->findOneById($activateLinkDTO),
            self::STRING => $this->activationLinkRepository->findOneByUuid($activateLinkDTO),
            default => null,
        };
    }
}
