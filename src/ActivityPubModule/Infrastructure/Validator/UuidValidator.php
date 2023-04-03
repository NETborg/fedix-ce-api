<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Validator;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Validator\UuidValidatorInterface;
use Symfony\Component\Uid\Uuid;

class UuidValidator implements UuidValidatorInterface
{

    public function isValidUuid(string $identifier): bool
    {
        return Uuid::isValid($identifier);
    }
}
