<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Tool;

interface UuidGeneratorInterface
{
    public function generate(): string;
}
