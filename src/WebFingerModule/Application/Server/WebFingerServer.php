<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Application\Server;

use Netborg\Fediverse\Api\Shared\Application\QueryBus\Query\GetWebFingerDetailsQuery;
use Netborg\Fediverse\Api\Shared\Domain\Model\DTO\GetWebFingerDetailsDTO;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryBusInterface;
use Netborg\Fediverse\Api\WebFingerModule\Application\Exception\ResourceNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WebFingerServer implements WebFingerServerInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
        private readonly LoggerInterface $logger
    ) {
    }

    public function resolve(Request $request): JsonResponse
    {
        $resource = urldecode((string) $request->query->get('resource'));
        /** @var string[]|null $rel */
        $rel = $request->query->get('rel');

        $dto = new GetWebFingerDetailsDTO($resource, $rel);

        $errors = $this->validator->validate($dto);

        if ($errors->count()) {
            throw new ValidationFailedException($dto, $errors);
        }

        $result = $this->queryBus->handle(new GetWebFingerDetailsQuery($dto));

        if (!$result) {
            $this->logger->error(sprintf('Unable to resolve WebFinger result for %s!', $resource));
            throw ResourceNotFoundException::resource($resource);
        }

        return new JsonResponse(
            data: $this->serializer->serialize($result, 'json'),
            json: true
        );
    }
}
