<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ModelNotFoundException extends HttpException
{
    public static function create(string $model): self
    {
        return new static(Response::HTTP_NOT_FOUND, sprintf('%s not found!', $model));
    }
}
