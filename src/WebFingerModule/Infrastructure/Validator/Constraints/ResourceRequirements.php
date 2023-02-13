<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Infrastructure\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Compound;
use Symfony\Component\Validator\Constraints as Assert;

#[\Attribute]
class ResourceRequirements extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\NotBlank(message: 'Resource must be provided and can not be an empty string'),
            new Assert\Regex(pattern: '/^(acct|https?):.+/i', message: 'Unsupported resource scheme. Sorry!'),
        ];
    }
}
