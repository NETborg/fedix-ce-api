<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Infrastructure\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;

abstract class AbstractController extends SymfonyAbstractController
{
    /** @return string[] */
    protected function getClientScopes(): array
    {
        try {
            return $this->container->get('security.token_storage')?->getToken()->getAttribute('scopes') ?? [];
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            return [];
        }
    }
}
