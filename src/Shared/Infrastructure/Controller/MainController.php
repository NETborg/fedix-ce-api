<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;
use Twig\Environment;

class MainController extends AbstractController
{
    public function main(): JsonResponse
    {
        return new JsonResponse(['test' => 'OK']);
    }

    public function previewEmail(string $email, Environment $twig): Response
    {
        $template = match ($email) {
            'activation' => $twig->render('email/activation_link.html.twig', ['activationLinkUuid' => Uuid::v7()->toRfc4122()]),
            default => ''
        };

        return new Response($template);
    }
}
