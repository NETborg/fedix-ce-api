<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Infrastructure\Util;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationListToArrayConverter
{
    public static function convert(ConstraintViolationListInterface $violationList): array
    {
        $errors = [];
        foreach ($violationList as $violation) {
            $errors[$violation->getPropertyPath()] = (string)$violation->getMessage();
        }

        return $errors;
    }
}
