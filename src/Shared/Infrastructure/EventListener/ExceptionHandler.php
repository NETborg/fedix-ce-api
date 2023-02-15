<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Infrastructure\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AsEventListener]
class ExceptionHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function __invoke(ExceptionEvent $exceptionEvent): void
    {
        $throwable = $exceptionEvent->getThrowable();
        $this->logger->error($throwable->getMessage(), $throwable->getTrace());

        if ($throwable instanceof ValidationFailedException) {
            $exceptionEvent->setResponse(new JsonResponse(
                data: $this->serializer->serialize($throwable->getViolations(), 'json'),
                status: Response::HTTP_BAD_REQUEST,
                json: true
            ));

            return;
        }

        $httpStatus = match (true) {
            is_a($throwable, HttpException::class) => $throwable->getStatusCode(),
            default => 500
        };

        $exceptionEvent->setResponse(new JsonResponse([
            'error' => $throwable->getMessage(),
            'code' => $throwable->getCode(),
        ], $httpStatus));
    }
}
