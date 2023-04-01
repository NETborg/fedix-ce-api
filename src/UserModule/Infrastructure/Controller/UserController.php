<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\Controller;

use Netborg\Fediverse\Api\Shared\Application\CommandBus\Command\RegisterUserCommand;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandBusInterface;
use Netborg\Fediverse\Api\Shared\Domain\Model\DTO\RegisterUserDTO;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryBusInterface;
use Netborg\Fediverse\Api\UserModule\Application\QueryBus\Query\GetUserQuery;
use Netborg\Fediverse\Api\Shared\Infrastructure\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function getAction(string $identifier): JsonResponse
    {
        $user = $this->queryBus->handle(new GetUserQuery($identifier));

        if (!$user) {
            throw new NotFoundHttpException(sprintf('User with username `%s` not found!', $identifier));
        }

        return new JsonResponse($this->serializer->serialize(
            $user,
            'json',
            [AbstractNormalizer::GROUPS => array_merge($this->getClientScopes(), ['get'])]
        ), json: true);
    }

    public function createAction(Request $request): JsonResponse
    {
        /** @var RegisterUserDTO $registerUserDTO */
        $registerUserDTO = $this->serializer->deserialize(
            data: $request->getContent(),
            type: RegisterUserDTO::class,
            format: 'json'
        );

        $errors = $this->validator->validate(value: $registerUserDTO);
        if (count($errors)) {
            throw new ValidationFailedException($registerUserDTO, $errors);
        }

        $user = $this->commandBus->handle(new RegisterUserCommand($registerUserDTO));

        return new JsonResponse($this->serializer->serialize(
            data: $user,
            format: 'json',
            context: [AbstractNormalizer::GROUPS => ['registration']]
        ), json: true);
    }
}
