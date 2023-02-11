<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Controller\Api\ActivityPub\Person;

use Netborg\Fediverse\Api\Entity\ActivityPub\Actor;
use Netborg\Fediverse\Api\Interfaces\ActivityPub\PersonFactoryInterface;
use Netborg\Fediverse\Api\Interfaces\Repository\ActorRepositoryInterface;
use Netborg\Fediverse\Api\Interfaces\Sanitiser\UsernameSanitiserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

class PersonController extends AbstractController
{
    public function __construct(
        private readonly ActorRepositoryInterface $actorRepository,
        private readonly UsernameSanitiserInterface $sanitiser,
        private readonly PersonFactoryInterface $personFactory,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function getAction(string $identifier): JsonResponse
    {
        $username = $this->sanitiser->deprefixise($identifier, true);

        $person = $this->actorRepository->findOneByPreferredUsername($username);

        if (!$person) {
            throw new NotFoundHttpException('Person not found');
        }

        return new JsonResponse($this->serializer->normalize(
            $this->personFactory->fromPersonEntity($person),
            'json',
        ));
    }
}
