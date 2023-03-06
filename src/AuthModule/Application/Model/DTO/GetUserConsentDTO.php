<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Application\Model\DTO;

use Netborg\Fediverse\Api\AuthModule\Domain\Model\Client;
use Netborg\Fediverse\Api\UserModule\Domain\Model\User;

class GetUserConsentDTO
{
    public User|string|null $user = null;
    public Client|string|null $client = null;
}
