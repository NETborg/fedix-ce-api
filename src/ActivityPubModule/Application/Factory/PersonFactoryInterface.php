<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\Factory;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity\Person as PersonEntity;

interface PersonFactoryInterface
{
    public function fromJsonString(string $json, Person $subject = null, array $context = []): Person;

    public function fromArray(array $data, Person $subject = null, array $context = []): Person;

    public function fromPersonEntity(PersonEntity $entity, Person $subject = null): Person;
}
