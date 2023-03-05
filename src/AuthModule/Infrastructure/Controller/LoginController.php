<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\Controller;

use Netborg\Fediverse\Api\Shared\Infrastructure\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    public function __construct(
        private readonly AuthenticationUtils $authenticationUtils,
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
    ) {
    }

    public function getAction(): Response
    {
        return new JsonResponse(['_csrf_token' => (string)$this->csrfTokenManager->getToken('authenticate')]);
    }

    public function postAction(): JsonResponse
    {
        if ($this->getUser()) {
            return new JsonResponse(['authenticated' => 'success']);
        }

        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return new JsonResponse([
            'error' => $error,
            'last_username' => $lastUsername
        ], Response::HTTP_BAD_REQUEST);
    }
}
