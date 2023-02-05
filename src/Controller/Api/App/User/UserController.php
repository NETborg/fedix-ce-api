<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Controller\Api\App\User;

use Netborg\Fediverse\Api\Entity\User;
use Netborg\Fediverse\Api\Interfaces\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly UserRepositoryInterface $userRepository,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function getAction(string $identifier): JsonResponse
    {
        $user = $this->userRepository->findByUsername($identifier);

        if (!$user) {
            throw new NotFoundHttpException(sprintf('User with username `%s` not found!', $identifier));
        }

        return new JsonResponse($this->serializer->normalize($user, 'json'));
    }

    public function createAction(Request $request): JsonResponse
    {
        $user = $this->serializer->deserialize(
            data: $request->getContent(),
            type: User::class,
            format: 'json',
            context: [AbstractNormalizer::GROUPS => ['Create']]
        );

        $errors = $this->validator->validate(value: $user, groups: ['Create']);
        if (count($errors)) {
            return new JsonResponse($this->serializer->normalize($errors, 'json'), Response::HTTP_BAD_REQUEST);
        }

        $this->userRepository->save($user, true);

        return new JsonResponse($this->serializer->normalize(
            $user,
            'json',
            [AbstractNormalizer::GROUPS => ['Default']]
        ));
    }
}
