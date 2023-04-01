<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\Model\DTO;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;

class CreatePersonDTO
{
    public ?Person $person = null;
    public ?string $userIdentifier = null;
}
