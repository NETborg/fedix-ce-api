<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Service;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Dispatcher\EventDispatcherInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Exception\UnauthorizedException;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Exception\ValidationException;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Logger\LoggerInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\DTO\UpdatePersonDetailsDTO;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Repository\PersonRepositoryInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Validator\PersonValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class PersonUpdateDetailsService
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly PersonValidatorInterface $validator,
        private readonly PersonRepositoryInterface $personRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function updateDetails(UpdatePersonDetailsDTO $detailsDTO): void
    {
        $person = $detailsDTO->person;

        $this->logger->debug(
            sprintf(
                '[%s] Processing Person details update [%s] - start',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );

        if (!$detailsDTO->owner || !$person->hasOwner($detailsDTO->owner)) {
            throw new UnauthorizedException('You are unauthorized to update this person details.');
        }

        $this->updatePerson($detailsDTO, $person);

        try {
            $this->validator->validate($person, [AbstractNormalizer::GROUPS => ['update']]);
        } catch (ValidationException $exception) {

            throw $exception;
        }

        $this->personRepository->update($person);
    }

    private function updatePerson(UpdatePersonDetailsDTO $detailsDTO, Person $person): void
    {
        $person
            ->setName($detailsDTO->name)
            ->setNameMap($detailsDTO->nameMap)
            ->setSummary($detailsDTO->summary)
            ->setSummaryMap($detailsDTO->summaryMap)
            ->setContent($detailsDTO->content)
            ->setContentMap($detailsDTO->contentMap);
    }
}
