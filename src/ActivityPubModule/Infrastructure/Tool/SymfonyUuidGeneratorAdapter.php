<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Tool;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Tool\UuidGeneratorInterface;
use Symfony\Component\Uid\Uuid;

class SymfonyUuidGeneratorAdapter implements UuidGeneratorInterface
{
    public function generate(): string
    {
        return Uuid::v7()->toRfc4122();
    }
}
