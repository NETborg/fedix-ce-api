<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\QueryBus\Query;

use Netborg\Fediverse\Api\Shared\Application\QueryBus\Query\AbstractQuery;

class GetUserQuery extends AbstractQuery
{
    public const NAME = 'user.get.by_username';

    protected string $name = self::NAME;
}
