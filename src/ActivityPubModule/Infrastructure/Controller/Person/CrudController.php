<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Controller\Person;

use Netborg\Fediverse\Api\ActivityPubModule\Application\CommandBus\Command\CreatePersonCommand;
use Netborg\Fediverse\Api\ActivityPubModule\Application\Model\DTO\CreatePersonDTO;
use Netborg\Fediverse\Api\ActivityPubModule\Application\QueryBus\Query\GetPersonQuery;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandBusInterface;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryBusInterface;
use Netborg\Fediverse\Api\Shared\Domain\Sanitiser\UsernameSanitiserInterface;
use Netborg\Fediverse\Api\Shared\Infrastructure\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class CrudController extends AbstractController
{
    public function __construct(
        private readonly UsernameSanitiserInterface $sanitiser,
        private readonly SerializerInterface $serializer,
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {
    }

    public function createAction(Request $request): JsonResponse
    {
        $person = $this->serializer->denormalize(
            data: $request->request->all(),
            type: Person::class,
            context: [
                AbstractNormalizer::GROUPS => ['create']
            ]
        );

        $dto = new CreatePersonDTO();
        $dto->person = $person;
        $dto->userIdentifier = $this->getUser()->getUserIdentifier();

        $this->commandBus->handle(new CreatePersonCommand($dto));

        $payload = $this->serializer->serialize(
            data: $person,
            format: 'json',
            context: [
                AbstractNormalizer::GROUPS => ['get']
            ]
        );

        return new JsonResponse(data: $payload, json: true);
    }

    public function getAction(string $identifier): JsonResponse
    {
        if (!Uuid::isValid($identifier)) {
            $identifier = $this->sanitiser->deprefixise($identifier, true);
        }

        $person = $this->queryBus->handle(new GetPersonQuery($identifier));

        if (!$person) {
            throw new NotFoundHttpException('Person not found');
        }

        return new JsonResponse($this->serializer->serialize(
            data: $person,
            format: 'json',
            context: [
                AbstractNormalizer::GROUPS => ['get']
            ]
        ), json: true);
    }
}
