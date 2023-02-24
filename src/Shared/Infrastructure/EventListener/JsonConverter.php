<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Shared\Infrastructure\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEventListener(event: RequestEvent::class)]
class JsonConverter
{
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if ('oauth2_token' === $request->get('_route') && 'json' === $request->getContentTypeFormat()) {
            $deserialized = json_decode($request->getContent(), true);
            $request->request->replace($deserialized);
        }
    }
}
