<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Builder;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Collection\ChangedFieldsCollectionInterface;

interface ChangedFieldsCollectionBuilderInterface
{
    public function reset(): self;

    public function add(string $fieldName, mixed $previous, mixed $current): self;

    public function build(): ChangedFieldsCollectionInterface;
}
