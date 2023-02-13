<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\Factory;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Subject\PublicKey;

interface PublicKeyFactoryInterface
{
    public function create(string $id, string $owner, string $publicKey): PublicKey;
}
