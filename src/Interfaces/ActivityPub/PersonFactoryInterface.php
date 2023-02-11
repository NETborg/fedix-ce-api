<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Interfaces\ActivityPub;

use Netborg\Fediverse\Api\Entity\ActivityPub\Actor;
use Netborg\Fediverse\Api\Model\ActivityPub\Actor\Person;

interface PersonFactoryInterface
{
    public function fromJsonString(string $json, Person $subject = null, array $context = []): Person;

    public function fromArray(array $data, Person $subject = null, array $context = []): Person;

    public function fromPersonEntity(Actor $entity, Person $subject = null): Person;
}
