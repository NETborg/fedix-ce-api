<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Logger;

interface LoggerInterface
{
    public function debug(string $message, array $trace = []): void;
    public function info(string $message, array $trace = []): void;
    public function warning(string $message, array $trace = []): void;
    public function error(string $message, array $trace = []): void;
    public function critical(string $message, array $trace = []): void;
}
