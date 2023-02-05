<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Controller\Api\ActivityPub\Person;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class PersonOutboxController extends AbstractController
{
    public function getAction(string $identifier): JsonResponse
    {
        return new JsonResponse(['person_outbox' => $identifier]);
    }
}
