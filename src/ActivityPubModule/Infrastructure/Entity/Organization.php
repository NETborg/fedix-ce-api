<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity;

use Doctrine\ORM\Mapping as ORM;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Repository\OrganizationRepository;

#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
class Organization extends Actor
{
}
