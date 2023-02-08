<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFinger;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

interface WebFingerServerInterface
{
    public function resolve(Request $request): JsonResponse;
}
