<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Tests;

use Netborg\Fediverse\Api\AuthModule\Infrastructure\AuthenticatedUser\DoctrineEntityUser;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryBusInterface;
use Netborg\Fediverse\Api\Tests\UserModule\Enum\RegularUserEnum;
use Netborg\Fediverse\Api\UserModule\Application\QueryBus\Query\GetUserQuery;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait UserLoggerTrait
{
    public const FIREWALL_API = 'api';
    public const FIREWALL_MAIN = 'main';

    protected function loginUser(
        KernelBrowser $client,
        string $username = RegularUserEnum::USERNAME,
        string $firewall = self::FIREWALL_API
    ): void {
        $queryBus = $this->getContainer()->get(QueryBusInterface::class);
        $user = $queryBus->handle(new GetUserQuery($username));

        if (!$user) {
            throw new \RuntimeException(sprintf('User `%s` not found! Unable to login user.', $username));
        }

        $user = new DoctrineEntityUser($user);
        $client->loginUser($user, $firewall);
    }
}
