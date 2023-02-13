<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Application\Service;

use Netborg\Fediverse\Api\WebFingerModule\Application\ResourceResolver\ResourceResolverInterface;
use Netborg\Fediverse\Api\WebFingerModule\Domain\Model\WebFingerResult;

interface WebFingerServiceInterface
{
    public static function registerResolver(ResourceResolverInterface $resolver): void;

    public function resolve(string $resource, ?array $rel = null): WebFingerResult;
}
