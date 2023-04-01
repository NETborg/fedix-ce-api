<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Validator\Person;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Exception\ValidationException;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Validator\PersonValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SymfonyPersonValidatorAdapter implements PersonValidatorInterface
{
    public function __construct(
        private readonly ValidatorInterface $validator,
        private readonly PersonValidationGroupsFactoryInterface $validationGroupsFactory,
    ) {
    }

    public function validate(Person $person, array $context): void
    {
        // TODO - to consider conversion $person to attributed DTO for easier validation?
        $validationGroups = $this->validationGroupsFactory->create($context);
        $errors = $this->validator->validate(value: $person, groups: $validationGroups);

        if ($errors->count() > 0) {
            throw new ValidationException($this->convertErrors($errors));
        }
    }

    private function convertErrors(ConstraintViolationListInterface $violationList): array
    {
        $errors = [];

        for ($i = 0; $i < $violationList->count(); $i++) {
            $violation = $violationList->get($i);
            $errors[$violation->getPropertyPath()] = (string) $violation->getMessage();
        }

        return $errors;
    }
}
