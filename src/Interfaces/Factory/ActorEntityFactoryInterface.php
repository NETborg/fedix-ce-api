<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Interfaces\Factory;

use Netborg\Fediverse\Api\Entity\ActivityPub\Actor;
use Netborg\Fediverse\Api\Entity\User;

interface ActorEntityFactoryInterface
{
    public function createFromUserEntity(User $user, string $type): Actor;
}
