<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Domain\Exception;

class ValidationFailedException extends JsonableException
{
    private const RESPONSE_CODE = 400;
    public const ERROR = 4000000;

    public function __construct(
        private readonly array $violations = [],
        int $httpResponseStatus = 400,
        string $message = 'Invalid data provided!',
        int $code = self::ERROR,
        ?\Throwable $previous = null
    ) {
        parent::__construct($httpResponseStatus, $message, $code, $previous);
    }

    public function getViolations(): array
    {
        return $this->violations;
    }

    protected function getResponseData(): array
    {
        return ['violations' => $this->getViolations()];
    }
}
