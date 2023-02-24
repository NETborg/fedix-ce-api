<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\UserProvider;

use Netborg\Fediverse\Api\AuthModule\Infrastructure\AuthenticatedUser\DoctrineEntityUser;
use Netborg\Fediverse\Api\UserModule\Application\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class DoctrineProvider implements UserProviderInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class)
    {
        return DoctrineEntityUser::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->userRepository->findOneByAnyIdentifier($identifier);

        if (!$user->isActive()) {
            throw new AuthenticationException('User needs to activate an account!', 401);
        }

        return new DoctrineEntityUser($user);
    }
}
