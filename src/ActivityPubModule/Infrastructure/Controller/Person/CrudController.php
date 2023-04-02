<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Controller\Person;

use Netborg\Fediverse\Api\ActivityPubModule\Application\CommandBus\Command\CreatePersonCommand;
use Netborg\Fediverse\Api\ActivityPubModule\Application\CommandBus\Command\UpdatePersonDetailsCommand;
use Netborg\Fediverse\Api\ActivityPubModule\Application\QueryBus\Query\GetPersonQuery;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Exception\ActorNotFoundException;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Exception\UnauthorizedException;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\DTO\CreatePersonDTO;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\DTO\UpdatePersonDetailsDTO;
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
        if (!$this->isGranted('ROLE_USER')) {
            throw new UnauthorizedException('Access denied!');
        }

        $person = $this->serializer->denormalize(
            data: $request->request->all(),
            type: Person::class,
            context: [
                AbstractNormalizer::GROUPS => ['create'],
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
                AbstractNormalizer::GROUPS => ['public', 'owner'],
            ]
        );

        return new JsonResponse(data: $payload, json: true);
    }

    public function getAction(string $identifier): JsonResponse
    {
        if (!Uuid::isValid($identifier)) {
            $identifier = $this->sanitiser->deprefixise($identifier, true);
        }

        /** @var Person $person */
        $person = $this->queryBus->handle(new GetPersonQuery($identifier));

        if (!$person) {
            throw new NotFoundHttpException('Person not found');
        }

        $userIdentifier = $this->getUser()?->getUserIdentifier();
        $groups = ['public'];
        if ($userIdentifier && $person->hasOwner($userIdentifier)) {
            array_push($groups, 'owner');
        }

        return new JsonResponse($this->serializer->serialize(
            data: $person,
            format: 'json',
            context: [
                AbstractNormalizer::GROUPS => $groups,
            ]
        ), json: true);
    }

    public function updateDetailsAction(string $identifier, Request $request): JsonResponse
    {
        if (!$this->isGranted('ROLE_USER')) {
            throw new UnauthorizedException('Access denied!');
        }

        if (!Uuid::isValid($identifier)) {
            $identifier = $this->sanitiser->deprefixise($identifier, true);
        }

        /** @var Person $person */
        $person = $this->queryBus->handle(new GetPersonQuery($identifier));

        if (!$person) {
            throw ActorNotFoundException::person($identifier);
        }

        $userIdentifier = $this->getUser()?->getUserIdentifier();

        /** @var UpdatePersonDetailsDTO $dto */
        $dto = $this->serializer->denormalize($request->request->all(), UpdatePersonDetailsDTO::class);
        $dto->owner = $userIdentifier;
        $dto->person = $person;

        $this->commandBus->handle(new UpdatePersonDetailsCommand($dto));

        $groups = ['public'];
        if ($userIdentifier && $person->hasOwner($userIdentifier)) {
            array_push($groups, 'owner');
        }

        return new JsonResponse($this->serializer->serialize(
            data: $person,
            format: 'json',
            context: [
                AbstractNormalizer::GROUPS => $groups,
            ]
        ), json: true);
    }
}
