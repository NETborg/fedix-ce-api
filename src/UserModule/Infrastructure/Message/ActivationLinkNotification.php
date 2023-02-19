<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\Message;

class ActivationLinkNotification
{
    public const ROUTING_KEY = 'activation';

    public function __construct(
        private readonly string $activationLinkId
    ) {
    }

    public function getActivationLinkId(): string
    {
        return $this->activationLinkId;
    }
}
