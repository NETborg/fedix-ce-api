<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ResourceNotFoundException extends HttpException
{
    public static function actor(string $identifier, int $httpStatus = 404): self
    {
        return new static($httpStatus, sprintf('No actor `%s` found on this server.', $identifier));
    }
}
