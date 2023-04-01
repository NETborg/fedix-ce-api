<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Validator\Person;

interface PersonValidationGroupsFactoryInterface
{
    public function create(array $context): array;
}
