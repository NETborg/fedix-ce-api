<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\ActivityPubModule\Application\Factory;

use Netborg\Fediverse\Api\Shared\Domain\Sanitiser\UsernameSanitiserInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractActorFactory
{
    protected string $className;

    public function __construct(
        protected readonly SerializerInterface $serializer,
        protected readonly RouterInterface $router,
        protected readonly PublicKeyFactoryInterface $publicKeyFactory,
        protected readonly UsernameSanitiserInterface $sanitiser,
        protected readonly string $frontendHost,
    ) {
    }

    protected function deserialize(string $json, array $context = [])
    {
        return $this->serializer->deserialize(data: $json, type: $this->className, format: 'json', context: $context);
    }

    protected function denormalize(array $data, array $context = [])
    {
        /* @phpstan-ignore-next-line */
        return $this->serializer->denormalize(data: $data, type: $this->className, context: $context);
    }

    protected function generateUrl(string $routeName, array $options = []): string
    {
        return $this->router->generate(
            $routeName,
            $options,
            RouterInterface::ABSOLUTE_URL
        );
    }

    protected function merge(object $source, object $target): void
    {
        $reflection = new \ReflectionClass($this->className);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        $setters = array_filter($methods, fn (\ReflectionMethod $reflectionMethod) => str_starts_with($reflectionMethod->getName(), 'set'));

        foreach ($setters as $reflectionMethod) {
            $getter = str_replace('set', 'get', $reflectionMethod->getName());
            if ($reflection->hasMethod($getter)) {
                $target->{$reflectionMethod->getName()}($source->{$getter}());
                continue;
            }

            $iser = str_replace('set', 'is', $reflectionMethod->getName());
            if ($reflection->hasMethod($iser)) {
                $target->{$reflectionMethod->getName()}($source->{$iser}());
                continue;
            }

            $haser = str_replace('set', 'has', $reflectionMethod->getName());
            if ($reflection->hasMethod($haser)) {
                $target->{$reflectionMethod->getName()}($source->{$haser}());
            }
        }
    }
}
