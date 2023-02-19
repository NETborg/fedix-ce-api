<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\Exception;

class CacheDeleteException extends \Exception
{
    public static function create(string $cacheName): self
    {
        return new self(sprintf('Unable delete record from %s cache!', $cacheName));
    }
}
