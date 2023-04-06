<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Collection;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\DTO\ChangedFieldDTO;

interface ChangedFieldsCollectionInterface
{
    public function get(string $fieldName): ?ChangedFieldDTO;

    public function has(string $fieldName): bool;

    /** @return ChangedFieldDTO[] */
    public function all(): array;
}
