<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Domain\Exception;

use Netborg\Fediverse\Api\Shared\Domain\Exception\ValidationFailedException;

class ValidationException extends ValidationFailedException
{
    public const ERROR = 4000201;

    public function __construct(
        array $violations = [],
        string $message = 'Invalid data provided!',
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            violations: $violations,
            message: $message,
            code: self::ERROR,
            previous: $previous
        );
    }
}
