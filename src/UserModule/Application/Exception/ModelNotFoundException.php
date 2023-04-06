<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\Exception;

use Netborg\Fediverse\Api\Shared\Domain\Exception\JsonableException;
use Symfony\Component\HttpFoundation\Response;

class ModelNotFoundException extends JsonableException
{
    public static function create(string $model): self
    {
        return new self(Response::HTTP_NOT_FOUND, sprintf('%s not found!', $model));
    }
}
