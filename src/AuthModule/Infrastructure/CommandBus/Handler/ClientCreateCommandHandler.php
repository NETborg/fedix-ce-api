<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\CommandBus\Handler;

use League\Bundle\OAuth2ServerBundle\Manager\ClientManagerInterface;
use Netborg\Fediverse\Api\AuthModule\Application\Factory\ClientFactory;
use Netborg\Fediverse\Api\AuthModule\Application\Factory\LeagueClientFactory;
use Netborg\Fediverse\Api\AuthModule\Domain\Model\Client;
use Netborg\Fediverse\Api\AuthModule\Infrastructure\CommandBus\Command\CreateClientCommand;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\Command\CommandInterface;
use Netborg\Fediverse\Api\Shared\Domain\CommandBus\CommandHandlerInterface;

class ClientCreateCommandHandler implements CommandHandlerInterface
{
    public const NAME = 'client.create';

    public function __construct(
        private readonly ClientManagerInterface $clientManager,
        private readonly LeagueClientFactory $leagueClientFactory,
        private readonly ClientFactory $clientFactory,
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function supports(string $command, string $subjectType): bool
    {
        return CreateClientCommand::NAME === $command
            && Client::class === $subjectType;
    }

    public function handle(CommandInterface $command): mixed
    {
        /** @var Client $client */
        $client = $command->getSubject();
        $leagueClient = $this->leagueClientFactory->fromClient($client);

        $this->clientManager->save($leagueClient);
        $this->clientFactory->fromLeagueModel($leagueClient, $client);

        return true;
    }
}
