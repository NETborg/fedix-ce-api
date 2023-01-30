<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

#[AsEventListener]
class ExceptionHandler
{
    public function __invoke(ExceptionEvent $exceptionEvent): void
    {
        $throwable = $exceptionEvent->getThrowable();

        $httpStatus = match (true) {
            is_a($throwable, HttpException::class) => $throwable->getStatusCode(),
            default => 500
        };

        $exceptionEvent->setResponse(new JsonResponse([
            'error' => $throwable->getMessage(),
            'code' => $throwable->getCode()
        ], $httpStatus));
    }
}
