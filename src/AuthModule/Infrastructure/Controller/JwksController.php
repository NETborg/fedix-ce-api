<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\Controller;

use Netborg\Fediverse\Api\Shared\Infrastructure\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class JwksController extends AbstractController
{
    public function __construct(
        private readonly string $privateKeyPath
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $publicKey = openssl_pkey_get_public(file_get_contents($this->privateKeyPath));
        $details = openssl_pkey_get_details($publicKey);

        $jwks = [
            'keys' => [
                [
                    'kty' => 'RSA',
                    'alg' => 'RS256',
                    'use' => 'sig',
                    'kid' => '1',
                    'n' => strtr(rtrim(base64_encode($details['rsa']['n']), '='), '+/', '-_'),
                    'e' => strtr(rtrim(base64_encode($details['rsa']['e']), '='), '+/', '-_'),
                ],
            ],
        ];

        return new JsonResponse($jwks);
    }
}
