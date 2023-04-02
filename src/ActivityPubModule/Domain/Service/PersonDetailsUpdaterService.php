<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Service;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Builder\ChangedFieldsCollectionBuilderInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Collection\ChangedFieldsCollectionInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Context\Context;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Context\ContextAction;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Dispatcher\EventDispatcherInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\Person\PersonPostUpdate;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Exception\UnauthorizedException;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Exception\ValidationException;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Logger\LoggerInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\DTO\UpdatePersonDetailsDTO;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Repository\PersonRepositoryInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Validator\PersonValidatorInterface;

readonly class PersonDetailsUpdaterService
{
    public function __construct(
        private LoggerInterface $logger,
        private PersonValidatorInterface $validator,
        private PersonRepositoryInterface $personRepository,
        private EventDispatcherInterface $eventDispatcher,
        private ChangedFieldsCollectionBuilderInterface $changedFieldsCollectionBuilder,
    ) {
    }

    public function updateDetails(UpdatePersonDetailsDTO $detailsDTO): void
    {
        $person = $detailsDTO->person;

        if (!($person instanceof Person)) {
            throw new \RuntimeException('Required Person object is missing!');
        }

        $this->logger->debug(
            sprintf(
                '[%s] Processing Person details update [%s] - start',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );

        if (!$detailsDTO->owner || !$person->hasOwner($detailsDTO->owner)) {
            $this->logger->debug(
                sprintf(
                    '[%s] Processing Person details update [%s] - user is unauthorized',
                    __METHOD__,
                    $person->getPreferredUsername()
                )
            );
            throw new UnauthorizedException('You are unauthorized to update this person details.');
        }

        $changedFieldsCollection = $this->createChangeFieldsCollection($person, $detailsDTO);
        $this->updatePerson($detailsDTO, $person);

        $this->logger->debug(
            sprintf(
                '[%s] Validating updated Person [%s]',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );
        try {
            $this->validator->validate($person, [Context::ACTION => ContextAction::PARTIAL_UPDATE]);
        } catch (ValidationException $exception) {
            $this->logger->debug(
                sprintf(
                    '[%s] Validating updated Person [%s] - failed',
                    __METHOD__,
                    $person->getPreferredUsername()
                )
            );
            throw $exception;
        }
        $this->logger->debug(
            sprintf(
                '[%s] Validating updated Person [%s] - passed',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );

        $this->logger->debug(
            sprintf(
                '[%s] Updating Person [%s]',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );
        $this->personRepository->update($person);
        $this->logger->debug(
            sprintf(
                '[%s] Updating Person [%s] - updated',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );

        $this->logger->debug(
            sprintf(
                '[%s] Event dispatch PersonPostUpdate [%s]',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );
        $this->eventDispatcher->dispatch(new PersonPostUpdate($person, $changedFieldsCollection));
        $this->logger->debug(
            sprintf(
                '[%s] Event Dispatch PersonPostUpdate [%s] - dispatched',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );
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

    private function createChangeFieldsCollection(Person $person, UpdatePersonDetailsDTO $detailsDTO): ChangedFieldsCollectionInterface
    {
        if ($person->getName() !== $detailsDTO->name) {
            $this->changedFieldsCollectionBuilder->add(
                fieldName: 'name',
                previous: $person->getName(),
                current: $detailsDTO->name
            );
        }
        if ($person->getNameMap() !== $detailsDTO->nameMap) {
            $this->changedFieldsCollectionBuilder->add(
                fieldName: 'nameMap',
                previous: $person->getNameMap(),
                current: $detailsDTO->nameMap
            );
        }
        if ($person->getSummary() !== $detailsDTO->summary) {
            $this->changedFieldsCollectionBuilder->add(
                fieldName: 'summary',
                previous: $person->getSummary(),
                current: $detailsDTO->summary
            );
        }
        if ($person->getSummaryMap() !== $detailsDTO->summaryMap) {
            $this->changedFieldsCollectionBuilder->add(
                fieldName: 'summaryMap',
                previous: $person->getSummaryMap(),
                current: $detailsDTO->summaryMap
            );
        }
        if ($person->getContent() !== $detailsDTO->content) {
            $this->changedFieldsCollectionBuilder->add(
                fieldName: 'content',
                previous: $person->getContent(),
                current: $detailsDTO->content
            );
        }
        if ($person->getContentMap() !== $detailsDTO->contentMap) {
            $this->changedFieldsCollectionBuilder->add(
                fieldName: 'contentMap',
                previous: $person->getContentMap(),
                current: $detailsDTO->contentMap
            );
        }

        return $this->changedFieldsCollectionBuilder->build();
    }
}
