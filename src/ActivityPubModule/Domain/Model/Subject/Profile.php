<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Subject;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\ObjectType;

class Profile extends ObjectType
{
    public const TYPE = 'Profile';

    protected static string $type = self::TYPE;

    protected ObjectType|string|null $describes = null;

    public function getDescribes(): string|ObjectType|null
    {
        return $this->describes;
    }

    public function setDescribes(string|ObjectType|null $describes): Profile
    {
        $this->describes = $describes;

        return $this;
    }
}
