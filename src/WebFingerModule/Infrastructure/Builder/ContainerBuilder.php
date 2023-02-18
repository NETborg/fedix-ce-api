<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Infrastructure\Builder;

use Netborg\Fediverse\Api\Shared\Infrastructure\ContainerBuilder\ContainerBuilderInterface;
use Netborg\Fediverse\Api\WebFingerModule\Infrastructure\DependencyInjection\CompilerPass\WebFingerResourceResolverPass;

class ContainerBuilder implements ContainerBuilderInterface
{
    public function build(\Symfony\Component\DependencyInjection\ContainerBuilder $container): void
    {
        $container->addCompilerPass(new WebFingerResourceResolverPass());
    }
}
