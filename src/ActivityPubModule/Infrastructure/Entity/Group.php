<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity;

use Doctrine\ORM\Mapping as ORM;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Repository\GroupRepository;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
class Group extends Actor
{
}
