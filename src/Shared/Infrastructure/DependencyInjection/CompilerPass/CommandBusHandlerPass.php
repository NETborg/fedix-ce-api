<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Infrastructure\DependencyInjection\CompilerPass;

use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandBusInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CommandBusHandlerPass implements CompilerPassInterface
{
    public const TAG = 'command_bus.handler';

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(CommandBusInterface::class)) {
            return;
        }

        $service = $container->findDefinition(CommandBusInterface::class);

        foreach ($container->findTaggedServiceIds(self::TAG) as $id => $tags) {
            $service->addMethodCall('registerCommandHandler', [new Reference($id)]);
        }
    }
}
