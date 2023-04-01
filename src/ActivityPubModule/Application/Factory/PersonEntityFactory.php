<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\Factory;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity\Person as PersonEntity;
use Symfony\Component\Uid\Uuid;

class PersonEntityFactory extends AbstractActorFactory implements PersonEntityFactoryInterface
{
    protected string $className = PersonEntity::class;

    public function createFromJsonString(string $json, PersonEntity $subject = null, array $context = []): PersonEntity
    {
        /** @var PersonEntity $personEntity */
        $personEntity = $this->deserialize($json, $context);

        if ($subject) {
            $this->merge($personEntity, $subject);

            return $subject;
        }

        return $personEntity;
    }

    public function createFromArray(array $data, PersonEntity $subject = null, array $context = []): PersonEntity
    {
        /** @var PersonEntity $personEntity */
        $personEntity = $this->denormalize($data, $context);

        if ($subject) {
            $this->merge($personEntity, $subject);

            return $subject;
        }

        return $personEntity;
    }

    public function createFromDomainModel(Person $person, PersonEntity $subject = null): PersonEntity
    {
        $subject = $subject ?? new $this->className();
        if (Uuid::isValid($person->getId())) {
            $subject->setUuid($person->getId());
        }
        return $subject
            ->setPreferredUsername($person->getPreferredUsername())
            ->setName($person->getName())
            ->setNameMap($person->getNameMap())
            ->setType($person->getType())
            ->setSummary($person->getSummary())
            ->setSummaryMap($person->getSummaryMap())
            ->setContent($person->getContent())
            ->setContentMap($person->getContentMap())
            ->setPublicKey($person->getPublicKey()?->getPublicKeyPem())
            ->setUsers($person->getOwners())
        ;
    }
}
