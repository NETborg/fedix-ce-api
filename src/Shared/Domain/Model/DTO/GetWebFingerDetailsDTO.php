<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Domain\Model\DTO;

use Netborg\Fediverse\Api\WebFingerModule\Infrastructure\Validator\Constraints as Assert;

class GetWebFingerDetailsDTO
{
    public function __construct(
        #[Assert\ResourceRequirements]
        public ?string $resource = null,
        public ?array $rel = null
    ) {
    }
}
