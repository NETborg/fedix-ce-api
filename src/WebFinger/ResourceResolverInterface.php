<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFinger;

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
