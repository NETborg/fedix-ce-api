<?php

namespace Netborg\Fediverse\Api\Shared;

use Netborg\Fediverse\Api\Shared\Infrastructure\DependencyInjection\CompilerPass\CommandBusHandlerPass;
use Netborg\Fediverse\Api\Shared\Infrastructure\DependencyInjection\CompilerPass\QueryBusHandlerPass;
use Netborg\Fediverse\Api\WebFingerModule;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    use ContainerBuilderAwareTrait;

    protected function registerContainerBuilders(): void
    {
        self::registerContainerBuilder(new WebFingerModule\Infrastructure\Builder\ContainerBuilder());
    }

    protected function build(ContainerBuilder $container)
    {
        $this->registerContainerBuilders();

        foreach (self::$containerBuilders as $containerBuilder) {
            $containerBuilder->build($container);
        }

        $container->addCompilerPass(new CommandBusHandlerPass());
        $container->addCompilerPass(new QueryBusHandlerPass());

        parent::build($container);
    }
}
