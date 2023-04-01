<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\DTO;

use Netborg\Fediverse\Api\ActivityPubModule\Domain\Model\Actor\Person;
use Symfony\Component\Validator\Constraints as Assert;

class UpdatePersonDetailsDTO
{
    public ?Person $person = null;

    #[Assert\NotBlank]
    public ?string $name = null;
    public ?array $nameMap = null;
    public ?string $summary = null;
    public ?array $summaryMap = null;
    public ?string $content = null;
    public ?array $contentMap = null;

    public ?string $owner = null;
}
