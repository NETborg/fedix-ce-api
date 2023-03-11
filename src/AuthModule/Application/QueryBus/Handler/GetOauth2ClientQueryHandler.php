<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Application\QueryBus\Handler;

use Doctrine\ORM\EntityManagerInterface;
use League\Bundle\OAuth2ServerBundle\Model\Client as LeagueClient;
use Netborg\Fediverse\Api\AuthModule\Application\Factory\ClientFactory;
use Netborg\Fediverse\Api\AuthModule\Application\QueryBus\Query\GetOauth2ClientQuery;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\Query\QueryInterface;
use Netborg\Fediverse\Api\Shared\Domain\QueryBus\QueryHandlerInterface;

class GetOauth2ClientQueryHandler implements QueryHandlerInterface
{
    public const NAME = 'oauth2_client.get';
    private const STRING = 'string';

    public function __construct(
        private readonly EntityManagerInterface $doctrineManager,
        private readonly ClientFactory $clientFactory,
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function supports(string $query, string $subjectType): bool
    {
        return GetOauth2ClientQuery::NAME === $query
            && self::STRING === $subjectType;
    }

    public function handle(QueryInterface $query): mixed
    {
        /** @var LeagueClient $leagueClient */
        $leagueClient = $this->doctrineManager->getRepository(LeagueClient::class)
            ->findOneBy(['identifier' => $query->getSubject()]);

        if (!$leagueClient) {
            return null;
        }

        return $this->clientFactory->fromLeagueModel($leagueClient);
    }
}
