<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Domain\Model\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterUserDTO
{
    public function __construct(
        public ?string $firstName = null,
        public ?string $lastName = null,
        #[Assert\NotBlank]
        public ?string $username = null,
        #[Assert\NotBlank]
        #[Assert\Email]
        public ?string $email = null,
        #[Assert\NotBlank]
        #[Assert\Length(min: 8, minMessage: 'Password must have min. 8 characters')]
        public ?string $password = null
    ) {
    }
}
