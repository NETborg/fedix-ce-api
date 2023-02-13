<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Infrastructure\DependencyInjection\CompilerPass;

use Netborg\Fediverse\Api\WebFingerModule\Application\Service\WebFingerServiceInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class WebFingerResourceResolverPass implements CompilerPassInterface
{
    public const TAG = 'webfinger.resource_resolver';

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(WebFingerServiceInterface::class)) {
            return;
        }

        $service = $container->findDefinition(WebFingerServiceInterface::class);

        foreach ($container->findTaggedServiceIds(self::TAG) as $id => $tags) {
            $service->addMethodCall('registerResolver', [new Reference($id)]);
        }
    }
}
