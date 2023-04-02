<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\Collection;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Collection\ChangedFieldsCollectionInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\DTO\ChangedFieldDTO;

readonly class ChangedFieldsCollection implements ChangedFieldsCollectionInterface
{
    /** @param ChangedFieldDTO[] $changedFields */
    public function __construct(
        private array $changedFields = []
    ) {
    }

    public function get(string $fieldName): ?ChangedFieldDTO
    {
        foreach ($this->changedFields as $changedField) {
            if ($changedField->fieldName === $fieldName) {
                return $changedField;
            }
        }

        return null;
    }

    public function has(string $fieldName): bool
    {
        foreach ($this->changedFields as $changedField) {
            if ($changedField->fieldName === $fieldName) {
                return true;
            }
        }

        return false;
    }

    public function all(): array
    {
        return $this->changedFields;
    }
}
