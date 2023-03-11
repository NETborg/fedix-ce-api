<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Application\QueryBus\Query;

use Netborg\Fediverse\Api\Shared\Application\QueryBus\Query\AbstractQuery;

class GetOauth2ClientQuery extends AbstractQuery
{
    public const NAME = 'query.oauth2_client.get';

    protected string $name = self::NAME;
}
