<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Application\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ResourceNotFoundException extends HttpException
{
    public static function actor(string $identifier, int $httpStatus = 404): self
    {
        return new static($httpStatus, sprintf('No actor `%s` found on this server.', $identifier));
    }

    public static function resource(string $resource, int $httpStatus = 404): self
    {
        return new static($httpStatus, sprintf('No resource `%s` found on this server.', $resource));
    }
}
