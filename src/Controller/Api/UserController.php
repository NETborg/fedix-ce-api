<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    public function getAction(string $identifier): JsonResponse
    {

        return new JsonResponse(['user' => $identifier]);
    }

    public function createAction(Request $request): JsonResponse
    {

        return new JsonResponse(['user_create' => 'OK']);
    }
}
