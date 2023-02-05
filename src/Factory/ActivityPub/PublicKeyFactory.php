<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Factory\ActivityPub;

use Netborg\Fediverse\Api\Interfaces\ActivityPub\PublicKeyFactoryInterface;
use Netborg\Fediverse\Api\Model\ActivityPub\Subject\PublicKey;

class PublicKeyFactory implements PublicKeyFactoryInterface
{
    public function create(string $id, string $owner, string $publicKey): PublicKey
    {
        return (new PublicKey())
            ->setId($id)
            ->setOwner($owner)
            ->setPublicKeyPem($publicKey)
        ;
    }
}
