<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Domain\Model\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ActivateLinkDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid(message: 'Invalid activation link')]
        public ?string $activationLink = null
    ) {
    }
}
