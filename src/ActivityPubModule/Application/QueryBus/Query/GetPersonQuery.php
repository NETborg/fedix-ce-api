<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\QueryBus\Query;

use Netborg\Fediverse\Api\Shared\Application\QueryBus\Query\AbstractQuery;

class GetPersonQuery extends AbstractQuery
{
    public const NAME = 'query.person.get';

    protected string $name = self::NAME;
}
