<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Service;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Context\Context;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Context\ContextAction;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Dispatcher\EventDispatcherInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\Person\PersonPostCreate;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Event\Person\PersonPreCreate;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Exception\PersonAlreadyExistsException;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Exception\ValidationException;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Logger\LoggerInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Repository\PersonRepositoryInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Tool\UuidGeneratorInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Validator\PersonValidatorInterface;

readonly class PersonCreatorService
{
    public function __construct(
        private LoggerInterface $logger,
        private PersonValidatorInterface $validator,
        private PersonRepositoryInterface $personRepository,
        private EventDispatcherInterface $eventDispatcher,
        private UuidGeneratorInterface $uuidGenerator,
    ) {
    }

    /**
     * @throws ValidationException|PersonAlreadyExistsException
     */
    public function createForOwner(Person $person, string $owner): void
    {
        $this->logger->debug(
            sprintf(
                '[%s] Processing Person creation [%s] - start',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );

        $this->logger->debug(
            sprintf(
                '[%s] Checking Person Existence Criteria [%s]',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );

        if ($this->personRepository->hasPerson($owner)) {
            $this->logger->debug(
                sprintf(
                    '[%s] Checking Person Existence Criteria [%s] - failed! User already owns a Person',
                    __METHOD__,
                    $person->getPreferredUsername()
                )
            );
            throw new PersonAlreadyExistsException();
        }

        $this->logger->debug(
            sprintf(
                '[%s] Checking Person Existence Criteria [%s] - passed',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );

        $person->setId($person->getId() ?? $this->uuidGenerator->generate());
        $person->addOwner($owner);

        $this->logger->debug(
            sprintf(
                '[%s] Validating Person [%s]',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );
        try {
            $this->validator->validate($person, [Context::ACTION => ContextAction::CREATE]);
        } catch (ValidationException $exception) {
            $this->logger->debug(
                sprintf(
                    '[%s] Validating Person [%s] - failed!',
                    __METHOD__,
                    $person->getPreferredUsername()
                )
            );
            throw $exception;
        }

        $this->logger->debug(
            sprintf(
                '[%s] Validating Person [%s] - passed',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );

        $this->logger->debug(
            sprintf(
                '[%s] Event Dispatch PersonPreCreate [%s]',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );
        $this->eventDispatcher->dispatch(new PersonPreCreate($person));
        $this->logger->debug(
            sprintf(
                '[%s] Event Dispatch PersonPreCreate [%s] - dispatched',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );

        $this->logger->debug(
            sprintf(
                '[%s] Creating Person [%s]',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );
        $this->personRepository->create($person);
        $this->logger->debug(
            sprintf(
                '[%s] Creating Person [%s] - created',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );

        $this->logger->debug(
            sprintf(
                '[%s] Event Dispatch PersonPostCreate [%s]',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );
        $this->eventDispatcher->dispatch(new PersonPostCreate($person));
        $this->logger->debug(
            sprintf(
                '[%s] Event Dispatch PersonPreCreate [%s] - dispatched',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );

        $this->logger->debug(
            sprintf(
                '[%s] Processing Person creation [%s] - finished',
                __METHOD__,
                $person->getPreferredUsername()
            )
        );
    }
}
