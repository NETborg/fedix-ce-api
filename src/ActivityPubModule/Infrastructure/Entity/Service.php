<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity;

use Doctrine\ORM\Mapping as ORM;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Repository\ServiceRepository;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service extends Actor
{
}
