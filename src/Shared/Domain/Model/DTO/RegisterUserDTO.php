<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Domain\Model\DTO;

class RegisterUserDTO
{
    public function __construct(
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $email = null,
        public ?string $password = null
    ) {
    }
}
