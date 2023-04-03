<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Service;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Logger\LoggerInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Repository\PersonRepositoryInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Validator\UuidValidatorInterface;

readonly class PersonGetterService
{
    public function __construct(
        private LoggerInterface $logger,
        private UuidValidatorInterface $uuidValidator,
        private PersonRepositoryInterface $personRepository,
    ) {
    }

    public function get(string $identifier): ?Person
    {
        if (empty($identifier)) {
            $this->logger->debug(
                sprintf(
                    '[%s] Processing Person getter - received empty Person identifier',
                    __METHOD__
                )
            );

            return null;
        }

        if ($this->uuidValidator->isValidUuid($identifier)) {
            $this->logger->debug(
                sprintf(
                    '[%s] Processing Person getter [%s] - searching for Person by UUID',
                    __METHOD__,
                    $identifier
                )
            );

            return $this->personRepository->findOneByUuid($identifier);
        }

        $this->logger->debug(
            sprintf(
                '[%s] Processing Person getter [%s] - searching for Person by preferredUsername',
                __METHOD__,
                $identifier
            )
        );

        return $this->personRepository->findOneByPreferredUsername($identifier);
    }
}
