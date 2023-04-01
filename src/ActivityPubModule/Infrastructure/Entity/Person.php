<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Entity;

use Doctrine\ORM\Mapping as ORM;
use Netborg\Fediverse\Api\ActivityPubModule\Infrastructure\Repository\PersonDoctrineRepository;

#[ORM\Entity(repositoryClass: PersonDoctrineRepository::class)]
class Person extends Actor
{
}
