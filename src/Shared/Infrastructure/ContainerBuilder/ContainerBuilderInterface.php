<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Infrastructure\ContainerBuilder;

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ContainerBuilderInterface
{
    public function build(ContainerBuilder $container): void;
}
