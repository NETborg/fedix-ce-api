<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Application\ResourceResolver;

use Netborg\Fediverse\Api\WebFingerModule\Application\Builder\WebFingerResultBuilderInterface;

interface ResourceResolverInterface
{
    public function supports(string $scheme, string $subject, string $resourceIdentifier, ?array $rel): bool;

    public function resolve(
        string $scheme,
        string $subject,
        string $resourceIdentifier,
        ?array $rel,
        WebFingerResultBuilderInterface $resultBuilder
    ): void;
}