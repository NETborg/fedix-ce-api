<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Infrastructure\DependencyInjection\CompilerPass;

use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryBusInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class QueryBusHandlerPass implements CompilerPassInterface
{
    public const TAG = 'query_bus.handler';

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(QueryBusInterface::class)) {
            return;
        }

        $service = $container->findDefinition(QueryBusInterface::class);

        foreach ($container->findTaggedServiceIds(self::TAG) as $id => $tags) {
            $service->addMethodCall('registerQueryHandler', [new Reference($id)]);
        }
    }
}
