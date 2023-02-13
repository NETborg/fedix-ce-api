<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\Controller;

use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity\Actor;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Factory\ActorEntityFactoryInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Repository\ActorRepositoryInterface;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Repository\UserRepositoryInterface;
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
        private readonly ActorRepositoryInterface $actorRepository,
        private readonly ActorEntityFactoryInterface $actorEntityFactory,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function getAction(string $identifier): JsonResponse
    {
        $user = $this->userRepository->findOneByUsername($identifier);

        if (!$user) {
            throw new NotFoundHttpException(sprintf('User with username `%s` not found!', $identifier));
        }

        return new JsonResponse($this->serializer->normalize(
            $user,
            'json',
            [AbstractNormalizer::GROUPS => ['User', 'Actors']]
        ));
    }

    public function createAction(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->serializer->deserialize(
            data: $request->getContent(),
            type: User::class,
            format: 'json',
            context: [AbstractNormalizer::GROUPS => ['Create']]
        );

        if ($user->getFirstName() || $user->getLastName()) {
            $user->setName(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));
        }

        $errors = $this->validator->validate(value: $user, groups: ['Create']);
        if (count($errors)) {
            return new JsonResponse($this->serializer->normalize($errors, 'json'), Response::HTTP_BAD_REQUEST);
        }

        $person = $this->actorEntityFactory->createFromUserEntity($user, Actor::PERSON);
        $check = $this->actorRepository->findOneByPreferredUsername($person->getPreferredUsername());

        while($check) {
            $newUsername = sprintf(
                '%s_%s',
                $person->getPreferredUsername(),
                substr(sha1(uniqid()), rand(0, 31), 8)
            );

            $person->setPreferredUsername($newUsername);
            $check = $this->actorRepository->findOneByPreferredUsername($newUsername);
        }

        $user->addActor($person);
        $this->userRepository->save($user, true);

        return new JsonResponse($this->serializer->normalize(
            $user,
            'json',
            [AbstractNormalizer::GROUPS => ['User', 'Created']]
        ));
    }
}
