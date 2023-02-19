<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\Cache;

use Netborg\Fediverse\Api\UserModule\Domain\Model\ActivationLink;

interface ActivationLinkCacheInterface
{
    public function set(ActivationLink $activationLink): void;

    public function get(string $identifier): ?ActivationLink;

    public function has(string $identifier): bool;

    public function delete(string $identifier): bool;
}
