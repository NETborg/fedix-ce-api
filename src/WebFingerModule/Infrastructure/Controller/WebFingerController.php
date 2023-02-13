<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Infrastructure\Controller;

use Netborg\Fediverse\Api\Shared\Infrastructure\Controller\AbstractController;
use Netborg\Fediverse\Api\WebFingerModule\Application\Server\WebFingerServerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WebFingerController extends AbstractController
{
    public function __construct(
        private readonly WebFingerServerInterface $webFingerServer
    ) {
    }

    #[Route(path: '/.well-known/webfinger', methods: ['GET'])]
    public function webfinger(Request $request): JsonResponse
    {
        return $this->webFingerServer->resolve($request);
    }
}
