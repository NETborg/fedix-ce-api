<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFinger;

use Netborg\Fediverse\Api\WebFinger\Model\WebFingerResult;

interface WebFingerServiceInterface
{
    public static function registerResolver(ResourceResolverInterface $resolver): void;

    public function resolve(string $resource, ?array $rel = null): WebFingerResult;
}
