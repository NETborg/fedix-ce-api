<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\Builder;

use Netborg\Fediverse\Api\ActivityPubModule\Application\Collection\ChangedFieldsCollection;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Builder\ChangedFieldsCollectionBuilderInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Collection\ChangedFieldsCollectionInterface;
use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\DTO\ChangedFieldDTO;

class ChangedFieldsCollectionBuilder implements ChangedFieldsCollectionBuilderInterface
{
    /** @var ChangedFieldDTO[] */
    private array $changedFields = [];

    public function reset(): self
    {
        $this->changedFields = [];

        return $this;
    }

    public function add(string $fieldName, mixed $previous, mixed $current): self
    {
        $this->changedFields[] = new ChangedFieldDTO($fieldName, $previous, $current);

        return $this;
    }

    public function build(): ChangedFieldsCollectionInterface
    {
        $collection = new ChangedFieldsCollection($this->changedFields);
        $this->reset();

        return $collection;
    }
}
