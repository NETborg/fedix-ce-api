<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Application\QueryBus\Query;

use Netborg\Fediverse\Api\Shared\Application\QueryBus\Query\AbstractQuery;

class GetOauth2UserConsentQuery extends AbstractQuery
{
    public const NAME = 'query.oauth2_user_consent.get';

    protected string $name = self::NAME;
}
