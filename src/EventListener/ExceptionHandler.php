<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

#[AsEventListener]
class ExceptionHandler
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(ExceptionEvent $exceptionEvent): void
    {
        $throwable = $exceptionEvent->getThrowable();
        $this->logger->error($throwable->getMessage(), $throwable->getTrace());

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
