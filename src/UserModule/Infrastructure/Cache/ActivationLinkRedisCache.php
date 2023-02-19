<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\UserModule\Infrastructure\Cache;

use Netborg\Fediverse\Api\UserModule\Domain\Model\ActivationLink;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Exception\CacheDeleteException;
use Netborg\Fediverse\Api\UserModule\Infrastructure\Exception\CacheWriteException;
use Predis\ClientInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ActivationLinkRedisCache implements ActivationLinkCacheInterface
{
    private const KEY_PREFIX = 'activation_link';
    private const EXPIRE_RESOLUTION = 'EXAT';
    private const FORMAT = 'json';

    private string $className = ActivationLink::class;

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ClientInterface $predis
    ) {
    }

    public function set(ActivationLink $activationLink): void
    {
        $serialized = $this->serializer->serialize(
            $activationLink,
            self::FORMAT,
            [AbstractNormalizer::GROUPS => ['save']]
        );

        $result = $this->predis->set(
            $this->makeKey($activationLink->getUuid()),
            $serialized,
            self::EXPIRE_RESOLUTION,
            \DateTimeImmutable::createFromFormat(
                \DateTimeInterface::RFC3339_EXTENDED,
                $activationLink->getExpiresAt()
            )->getTimestamp()
        );

        if ('OK' !== $result->getPayload()) {
            throw CacheWriteException::create('Redis - Activation Link');
        }
    }

    public function get(string $identifier): ?ActivationLink
    {
        $serialized = $this->predis->get($this->makeKey($identifier));

        if (!$serialized) {
            return null;
        }

        return $this->serializer->deserialize($serialized, $this->className, self::FORMAT);
    }

    public function has(string $identifier): bool
    {
        return (bool) $this->predis->exists($this->makeKey($identifier));
    }

    public function delete(string $identifier): bool
    {
        $result = (bool) $this->predis->del($this->makeKey($identifier));

        if (!$result) {
            throw CacheDeleteException::create('Redis - Activation Link');
        }

        return $result;
    }

    private function makeKey(string $identifier): string
    {
        return sprintf('%s:%s', self::KEY_PREFIX, $identifier);
    }
}
