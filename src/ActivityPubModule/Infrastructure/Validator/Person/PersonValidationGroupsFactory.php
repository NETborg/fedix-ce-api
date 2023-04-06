<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Validator\Person;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Context\Context;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Context\ContextAction;

readonly class PersonValidationGroupsFactory implements PersonValidationGroupsFactoryInterface
{
    private const CREATE = 'create';
    private const UPDATE = 'update';

    public function create(array $context): array
    {
        $groups = [];

        if (array_key_exists(Context::ACTION, $context)) {
            $groups[] = match ($context[Context::ACTION]) {
                ContextAction::CREATE => self::CREATE,
                ContextAction::PARTIAL_UPDATE || ContextAction::UPDATE => self::UPDATE,
                default => null,
            };
        }

        return $groups;
    }
}
