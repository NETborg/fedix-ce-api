<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Application\QueryBus\Query;

class GetWebFingerDetailsQuery extends AbstractQuery
{
    public const NAME = 'get.web_finger.details';

    protected string $name = self::NAME;
}
