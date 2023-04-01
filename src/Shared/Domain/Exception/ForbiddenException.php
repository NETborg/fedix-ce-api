<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Domain\Exception;

class ForbiddenException extends JsonableException
{
    public function __construct(
        int $httpResponseStatus = 403,
        string $message = "",
        int $code = 4030000,
        ?\Throwable $previous = null
    ) {
        parent::__construct($httpResponseStatus, $message, $code, $previous);
    }
}
