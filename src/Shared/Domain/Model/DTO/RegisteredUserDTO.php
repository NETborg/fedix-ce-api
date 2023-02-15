<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Domain\Model\DTO;

use Netborg\Fediverse\Api\UserModule\Domain\Model\User;

class RegisteredUserDTO
{
    public function __construct(
        public User $user,
        public bool $activationLinkSent,
    ) {
    }
}
