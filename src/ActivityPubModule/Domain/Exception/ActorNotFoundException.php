<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Exception;

use Netborg\Fediverse\Api\Shared\Domain\Exception\JsonableException;

class ActorNotFoundException extends JsonableException
{
    private const RESPONSE_CODE = 404;
    public const ERROR = 4040101;

    public static function person(string $personIdentifier): self
    {
        return new self(
            self::RESPONSE_CODE,
            sprintf('Person identified by %s not found!', $personIdentifier),
            self::ERROR
        );
    }
}
