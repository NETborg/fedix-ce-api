<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Domain\Exception;

class JsonableException extends \Exception implements \JsonSerializable
{
    private const RESPONSE_CODE = 500;
    public const ERROR = 5000000;

    public function __construct(
        protected int $httpResponseStatus = self::RESPONSE_CODE,
        string $message = "",
        int $code = self::ERROR,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getHttpResponseStatus(): int
    {
        return $this->httpResponseStatus;
    }

    protected function getResponseData(): array
    {
        return [];
    }

    public function jsonSerialize(): array
    {
        return array_merge($this->getResponseData(), [
            'code' => $this->getCode(),
            'error' => $this->getMessage(),
        ]);
    }
}
