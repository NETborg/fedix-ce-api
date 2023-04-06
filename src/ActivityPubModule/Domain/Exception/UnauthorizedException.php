<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Exception;

use Netborg\Fediverse\Api\Shared\Domain\Exception\ForbiddenException;

class UnauthorizedException extends ForbiddenException
{
    public const ERROR = 4030102;

    public function __construct(
        string $message = '',
        ?\Throwable $previous = null
    ) {
        parent::__construct(message: $message, code: self::ERROR, previous: $previous);
    }
}
