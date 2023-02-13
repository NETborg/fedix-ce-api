<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Subject;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\ObjectType;

class Tombstone extends ObjectType
{
    public const TYPE = 'Tombstone';

    protected static string $type = self::TYPE;

    protected string|null $formerType = null;
    protected string|\DateTimeInterface|null $deleted = null;

    public function getFormerType(): ?string
    {
        return $this->formerType;
    }

    public function setFormerType(?string $formerType): Tombstone
    {
        $this->formerType = $formerType;

        return $this;
    }

    public function getDeleted(): \DateTimeInterface|string|null
    {
        return $this->deleted;
    }

    public function setDeleted(\DateTimeInterface|string|null $deleted): Tombstone
    {
        $this->deleted = $deleted;

        return $this;
    }
}
