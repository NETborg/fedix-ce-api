<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Logger;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Logger\LoggerInterface;
use Psr\Log\LoggerInterface as PsrLogger;

class SymfonyLoggerAdapter implements LoggerInterface
{
    public function __construct(
        private readonly PsrLogger $logger
    ) {
    }

    public function debug(string $message, array $trace = []): void
    {
        $this->logger->debug($message, $trace);
    }

    public function info(string $message, array $trace = []): void
    {
        $this->logger->info($message, $trace);
    }

    public function warning(string $message, array $trace = []): void
    {
        $this->logger->warning($message, $trace);
    }

    public function error(string $message, array $trace = []): void
    {
        $this->logger->error($message, $trace);
    }

    public function critical(string $message, array $trace = []): void
    {
        $this->logger->critical($message, $trace);
    }
}
