<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Repository\Doctrine;

use Netborg\Fediverse\Api\ActivityPubModule\Application\Factory\PersonEntityFactoryInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Application\Factory\PersonFactoryInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Repository\PersonRepositoryInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity\Person as PersonEntity;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Repository\PersonDoctrineRepositoryInterface as DoctrineRepository;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PersonRepository implements PersonRepositoryInterface
{
    public function __construct(
        private readonly PersonEntityFactoryInterface $personEntityFactory,
        private readonly PersonFactoryInterface $personFactory,
        private readonly DoctrineRepository $doctrineRepository,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function find(string $identifier): ?Person
    {
        $personEntity = $this->doctrineRepository->findOneByUuid($identifier);

        if (!$personEntity) {
            $personEntity = $this->doctrineRepository->findOneByPreferredUsername($identifier);
        }

        return $personEntity
            ? $this->personFactory->fromPersonEntity($personEntity)
            : null;
    }

    public function create(Person $person): void
    {
        $personEntity = $this->personEntityFactory->createFromDomainModel($person);

        $errors = $this->validator->validate(value: $personEntity, groups: ['create']);
        if ($errors->count() > 0) {
            throw new ValidationFailedException($personEntity, $errors);
        }

        $this->doctrineRepository->save($personEntity, true);
    }

    public function findOneByUuid(string $uuid): ?Person
    {
        $personEntity = $this->doctrineRepository->findOneByUuid($uuid);

        return $personEntity
            ? $this->personFactory->fromPersonEntity($personEntity)
            : null;
    }

    public function findOneByPreferredUsername(string $preferredUsername): ?Person
    {
        $personEntity = $this->doctrineRepository->findOneByPreferredUsername($preferredUsername);

        return $personEntity
            ? $this->personFactory->fromPersonEntity($personEntity)
            : null;
    }

    public function findAllOwnedBy(string $owner): iterable
    {
        return array_map(
            static fn (PersonEntity $person) => $this->personFactory->fromPersonEntity($person),
            iterator_to_array($this->doctrineRepository->findForUser($owner))
        );
    }

    public function update(Person $person): void
    {
        $previous = $this->doctrineRepository->findOneByPreferredUsername($person->getPreferredUsername());
        $personEntity = $this->personEntityFactory->createFromDomainModel($person, $previous);

        $errors = $this->validator->validate(value: $personEntity, groups: ['update']);
        if ($errors->count() > 0) {
            throw new ValidationFailedException($personEntity, $errors);
        }

        $this->doctrineRepository->save($personEntity, true);
    }

    public function delete(Person|string $person): void
    {
        $personIdentifier = is_string($person) ? $person : $person->getPreferredUsername();

        $person = $this->doctrineRepository->findOneByPreferredUsername($personIdentifier);

        if (!$person) {
            throw new \RuntimeException(sprintf('Unable to find Person for deletion %s.', $person));
        }

        $this->doctrineRepository->remove($person, true);
    }

    public function hasPerson(string $owner): bool
    {
        return $this->doctrineRepository->hasForUser($owner);
    }
}
