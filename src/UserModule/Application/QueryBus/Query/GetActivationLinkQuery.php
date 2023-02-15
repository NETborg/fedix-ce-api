<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Application\QueryBus\Query;

use Netborg\Fediverse\Api\Shared\Application\QueryBus\Query\AbstractQuery;

class GetActivationLinkQuery extends AbstractQuery
{
    public const NAME = 'activation_link.get';

    protected string $name = self::NAME;
}
