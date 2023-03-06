<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\Controller;

use Netborg\Fediverse\Api\Shared\Infrastructure\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    public function getAction(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return new RedirectResponse($this->getParameter('frontend_host'));
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/login_form.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
