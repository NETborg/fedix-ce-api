<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Application\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ResourceOwnershipException extends HttpException
{
    public static function create(string $identifier, int $httpStatus = 400): self
    {
        return new self($httpStatus, sprintf('Resource `%s` doesn\'t belong to this server.', $identifier));
    }
}
