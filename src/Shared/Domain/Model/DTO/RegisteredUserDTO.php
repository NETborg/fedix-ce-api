<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Domain\Model\DTO;

use Netborg\Fediverse\Api\UserModule\Domain\Model\User;
use Symfony\Component\Serializer\Annotation\Groups;

class RegisteredUserDTO
{
    public function __construct(
        #[Groups(['registration'])]
        public User $user,
        #[Groups(['registration'])]
        public bool $activationLinkSent,
    ) {
    }
}
