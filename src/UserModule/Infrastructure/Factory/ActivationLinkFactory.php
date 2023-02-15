<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\Factory;

use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\ActivationLink;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;

class ActivationLinkFactory
{
    public function __construct(
        private readonly string $ttl = 'PT24H'
    ) {
    }

    public function create(User $user = null): ActivationLink
    {
        return (new ActivationLink())
            ->setUser($user)
            ->setExpiresAt((new \DateTimeImmutable())->add(new \DateInterval($this->ttl)));
    }
}
