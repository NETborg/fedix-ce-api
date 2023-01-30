<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route(path: '/api/v1', methods: ['GET'])]
    public function main(): JsonResponse
    {
        return new JsonResponse(['test' => 'OK']);
    }
}
