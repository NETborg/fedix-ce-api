<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\AuthenticatedUser;

use Netborg\Fediverse\Api\UserModule\Domain\Model\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DoctrineEntityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        private readonly User $user,
    ) {
    }

    public function getPassword(): ?string
    {
        return $this->user->getPassword();
    }

    public function getRoles(): array
    {
        return [
            'ROLE_USER',
        ];
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->user->getUuid();
    }
}
