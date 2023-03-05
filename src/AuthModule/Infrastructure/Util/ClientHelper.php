<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\Util;

final class ClientHelper
{
    public static function generateIdentifier(): string
    {
        return hash('md5', random_bytes(16));
    }

    public static function generateSecret(): string
    {
        return hash('sha512', random_bytes(32));
    }
}
