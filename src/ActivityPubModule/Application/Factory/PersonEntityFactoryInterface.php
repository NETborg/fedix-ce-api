<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\Factory;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity\Person as PersonEntity;

interface PersonEntityFactoryInterface
{
    public function createFromJsonString(string $json, PersonEntity $subject = null, array $context = []): PersonEntity;

    public function createFromArray(array $data, PersonEntity $subject = null, array $context = []): PersonEntity;

    public function createFromDomainModel(Person $person, PersonEntity $subject = null): PersonEntity;
}
