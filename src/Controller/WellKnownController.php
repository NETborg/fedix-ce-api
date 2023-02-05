<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Controller;

use Netborg\Fediverse\Api\Model\ActivityPub\Activity\Accept;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class WellKnownController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route(path: '/.well-known/webfinger', methods: ['GET'])]
    public function webfinger(Request $request): JsonResponse
    {
        $object = new Accept();
        $normalized = $this->serializer->normalize($object);

        return new JsonResponse([
            'webfinger' => 'OK',
            'ips' => $request->getClientIps(),
            'forwarded-for' => $request->headers->get('X-Forwarded-For'),
            'subject' => $normalized,
        ]);
    }
}
