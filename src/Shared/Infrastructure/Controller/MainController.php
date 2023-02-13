<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainController extends AbstractController
{
    public function main(): JsonResponse
    {
        return new JsonResponse(['test' => 'OK']);
    }
}
