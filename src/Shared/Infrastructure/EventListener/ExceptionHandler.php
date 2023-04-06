<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Infrastructure\EventListener;

use Netborg\Fediverse\Api\Shared\Domain\Exception\JsonableException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

#[AsEventListener]
class ExceptionHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(ExceptionEvent $exceptionEvent): void
    {
        $throwable = $exceptionEvent->getThrowable();
        $this->logger->error($throwable->getMessage(), $throwable->getTrace());

        if ($throwable instanceof JsonableException) {
            $exceptionEvent->setResponse(new JsonResponse(
                data: $throwable->jsonSerialize(),
                status: $throwable->getHttpResponseStatus()
            ));

            return;
        }

        $httpStatus = match (true) {
            is_a($throwable, HttpException::class) => $throwable->getStatusCode(),
            is_a($throwable, AuthenticationException::class) => $throwable->getCode() ?: 401,
            default => 500
        };

        $exceptionEvent->setResponse(new JsonResponse([
            'error' => $throwable->getMessage(),
            'code' => $throwable->getCode(),
        ], $httpStatus));
    }
}
