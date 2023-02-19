<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\Exception;

class CacheWriteException extends \Exception
{
    public static function create(string $cacheName): self
    {
        return new self(sprintf('Unable write record to %s cache!', $cacheName));
    }
}
