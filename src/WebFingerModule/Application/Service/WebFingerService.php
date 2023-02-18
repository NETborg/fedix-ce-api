<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Application\Service;

use Netborg\Fediverse\Api\WebFingerModule\Application\Builder\WebFingerResultBuilder;
use Netborg\Fediverse\Api\WebFingerModule\Application\Builder\WebFingerResultBuilderInterface;
use Netborg\Fediverse\Api\WebFingerModule\Application\ResourceResolver\ResourceResolverInterface;
use Netborg\Fediverse\Api\WebFingerModule\Domain\Model\WebFingerResult;

class WebFingerService implements WebFingerServiceInterface
{
    /** @var ResourceResolverInterface[] */
    private static array $resourceResolvers = [];

    public static function registerResolver(ResourceResolverInterface $resolver): void
    {
        self::$resourceResolvers[] = $resolver;
    }

    /** @param string[]|null $rel */
    public function resolve(string $resource, ?array $rel = null): WebFingerResult
    {
        [$scheme, $subject] = explode(':', $resource);

        $resourceBuilder = $this->createResultBuilder();

        foreach (self::$resourceResolvers as $resourceResolver) {
            if ($resourceResolver->supports($scheme, $subject, $resource, $rel)) {
                $resourceResolver->resolve($scheme, $subject, $resource, $rel, $resourceBuilder);
            }
        }

        return $resourceBuilder->build();
    }

    protected function createResultBuilder(): WebFingerResultBuilderInterface
    {
        return new WebFingerResultBuilder();
    }
}
