<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ResourceIdentifierException extends HttpException
{
    public static function create(string $identifier): self
    {
        return new static(400, sprintf('Resource identifier `%s` is invalid!', $identifier));
    }
}
