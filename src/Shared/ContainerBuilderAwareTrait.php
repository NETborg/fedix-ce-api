<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared;

use Netborg\Fediverse\Api\Shared\Infrastructure\ContainerBuilder\ContainerBuilderInterface;

trait ContainerBuilderAwareTrait
{
    /** @var ContainerBuilderInterface[] */
    private static array $containerBuilders = [];

    public static function registerContainerBuilder(ContainerBuilderInterface $containerBuilder): void
    {
        self::$containerBuilders[] = $containerBuilder;
    }

    abstract protected function registerContainerBuilders(): void;
}
