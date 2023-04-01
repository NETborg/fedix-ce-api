<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Validator;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Exception\ValidationException;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;

interface PersonValidatorInterface
{
    /** @throws ValidationException */
    public function validate(Person $person, array $context): void;
}
