<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidActivationLinkException extends HttpException
{
    public static function notFound(): self
    {
        return new static(Response::HTTP_NOT_FOUND, 'Invalid activation link!');
    }

    public static function expired(): self
    {
        return new static(Response::HTTP_UNAUTHORIZED, 'Activation link has expired!');
    }
}
