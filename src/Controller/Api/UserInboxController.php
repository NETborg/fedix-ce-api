<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserInboxController extends AbstractController
{
    public function getAction(string $identifier): JsonResponse
    {
        return new JsonResponse(['user_inbox' => $identifier]);
    }
}
