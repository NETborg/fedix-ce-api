<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Application\Server;

use Netborg\Fediverse\Api\Shared\Application\QueryBus\Query\GetWebFingerDetailsQuery;
use Netborg\Fediverse\Api\Shared\Domain\Model\DTO\GetWebFingerDetailsDTO;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryBusInterface;
use Netborg\Fediverse\Api\WebFingerModule\Application\QueryBus\Handler\WebFingerDetailsQueryHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WebFingerServer implements WebFingerServerInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly ValidatorInterface $validator,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function resolve(Request $request): JsonResponse
    {
        $resource = urldecode((string) $request->query->get('resource'));
        $rel = $request->query->get('rel');

        $dto = new GetWebFingerDetailsDTO($resource, $rel);

        $errors = $this->validator->validate($dto);

        if ($errors->count()) {
            return new JsonResponse(
                data: $this->serializer->serialize($errors, 'json'),
                status: Response::HTTP_BAD_REQUEST,
                json: true
            );
        }

        $result = $this->queryBus->handle(new GetWebFingerDetailsQuery($dto));

        if (1 === count($result) && isset($result[WebFingerDetailsQueryHandler::NAME])) {
            $result = $result[WebFingerDetailsQueryHandler::NAME];
        }

        return new JsonResponse(
            data: $this->serializer->serialize($result, 'json'),
            json: true
        );
    }
}
