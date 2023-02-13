<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Factory;

use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity\Actor;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Entity\User;

interface ActorEntityFactoryInterface
{
    public function createFromUserEntity(User $user, string $type): Actor;
}
