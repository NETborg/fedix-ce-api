<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Application\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidArgumentException extends HttpException
{
    public static function client(): self
    {
        return new self(400, 'Invalid or missing Client identifier!');
    }

    public static function user(): self
    {
        return new self(400, 'Invalid or missing User identifier!');
    }
}
