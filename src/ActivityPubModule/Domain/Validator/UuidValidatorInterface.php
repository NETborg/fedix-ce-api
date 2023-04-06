<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Validator;

interface UuidValidatorInterface
{
    public function isValidUuid(string $identifier): bool;
}
