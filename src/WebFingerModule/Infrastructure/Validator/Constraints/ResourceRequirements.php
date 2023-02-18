<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Infrastructure\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Compound;

#[\Attribute]
class ResourceRequirements extends Compound
{
    /**
     * @param array<string, mixed> $options
     *
     * @return Constraint[]
     */
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\NotBlank(message: 'Resource must be provided and can not be an empty string'),
            new Assert\Regex(pattern: '/^(acct|https?):.+/i', message: 'Unsupported resource scheme. Sorry!'),
        ];
    }
}
