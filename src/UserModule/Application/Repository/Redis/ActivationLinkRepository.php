<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\Repository\Redis;

use Netborg\Fediverse\Api\UserModule\Application\Repository\ActivationLinkRepositoryInterface;
use Netborg\Fediverse\Api\UserModule\Domain\Model\ActivationLink;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Cache\ActivationLinkCacheInterface;

class ActivationLinkRepository implements ActivationLinkRepositoryInterface
{
    public function __construct(
        private readonly ActivationLinkCacheInterface $cache,
    ) {
    }

    public function save(ActivationLink $activationLink): void
    {
        $this->cache->set($activationLink);
    }

    public function remove(ActivationLink $activationLink): void
    {
        $this->cache->delete($activationLink->getUuid());
    }

    public function findOneById(int $id): ?ActivationLink
    {
        return null;
    }

    public function findOneByUuid(string $uuid): ?ActivationLink
    {
        return $this->cache->get($uuid);
    }
}
