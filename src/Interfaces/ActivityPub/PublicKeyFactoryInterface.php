<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Interfaces\ActivityPub;

use Netborg\Fediverse\Api\Model\ActivityPub\Subject\PublicKey;

interface PublicKeyFactoryInterface
{
    public function create(string $id, string $owner, string $publicKey): PublicKey;
}
