<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\Exception;

use Netborg\Fediverse\Api\Shared\Domain\Exception\JsonableException;
use Symfony\Component\HttpFoundation\Response;

class InvalidActivationLinkException extends JsonableException
{
    public static function notFound(): self
    {
        return new self(
            httpResponseStatus: Response::HTTP_NOT_FOUND,
            message: 'Invalid activation link!',
            code: 4000201
        );
    }

    public static function expired(): self
    {
        return new self(
            httpResponseStatus: Response::HTTP_UNAUTHORIZED,
            message: 'Activation link has expired!',
            code: 4010201
        );
    }
}
