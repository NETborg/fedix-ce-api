<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\Factory;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity\Person as PersonEntity;

class PersonFactory extends AbstractActorFactory implements PersonFactoryInterface
{
    protected string $className = Person::class;

    public function fromJsonString(string $json, Person $subject = null, array $context = []): Person
    {
        /** @var Person $person */
        $person = $this->deserialize($json, $context);

        if ($subject) {
            $this->merge($person, $subject);

            return $subject;
        }

        return $person;
    }

    public function fromArray(array $data, Person $subject = null, array $context = []): Person
    {
        /** @var Person $person */
        $person = $this->denormalize($data, $context);

        if ($subject) {
            $this->merge($person, $subject);

            return $subject;
        }

        return $person;
    }

    public function fromPersonEntity(PersonEntity $entity, Person $subject = null): Person
    {
        $options = [
            'identifier' => $this->sanitiser->prefixise($entity->getPreferredUsername()),
        ];

        $id = $this->generateUrl('api_ap_v1_person_get', $options);
        $inbox = $this->generateUrl('api_ap_v1_person_inbox_get', $options);
        $outbox = $this->generateUrl('api_ap_v1_person_outbox_get', $options);
        $publicKey = $entity->getPublicKey()
            ? $this->publicKeyFactory->create(
                $this->generateUrl('api_ap_v1_person_pub_key_get', $options),
                $id,
                $entity->getPublicKey()
            )
            : null;

        $person = $subject ?? new Person();

        /* @phpstan-ignore-next-line */
        return $person
            ->setId($id)
            ->setName($entity->getName())
            ->setNameMap($entity->getNameMap())
            ->setPreferredUsername($entity->getPreferredUsername())
            ->setInbox($inbox)
            ->setOutbox($outbox)
            ->setSummary($entity->getSummary())
            ->setSummaryMap($entity->getSummaryMap())
            ->setPublicKey($publicKey)
            ->setOwners($entity->getUsers() ?? [])
        ;
    }
}
