<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Domain\Context;

class ContextAction
{
    public const CREATE = 'create';
    public const UPDATE = 'update';
    public const PARTIAL_UPDATE = 'partial_update';
    public const DELETE = 'delete';
}
