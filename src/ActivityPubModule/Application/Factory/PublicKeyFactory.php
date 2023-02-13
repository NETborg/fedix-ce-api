<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\Factory;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Subject\PublicKey;

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
