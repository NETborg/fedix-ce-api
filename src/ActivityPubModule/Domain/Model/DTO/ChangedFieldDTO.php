<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\DTO;

class ChangedFieldDTO
{
    public function __construct(
        public string $fieldName,
        public mixed $previous,
        public mixed $current,
    ) {
    }
}
