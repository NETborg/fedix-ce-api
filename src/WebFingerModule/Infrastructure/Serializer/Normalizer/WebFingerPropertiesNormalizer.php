<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\WebFingerModule\Infrastructure\Serializer\Normalizer;

use Netborg\Fediverse\Api\WebFingerModule\Domain\Model\WebFingerProperties;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class WebFingerPropertiesNormalizer extends AbstractNormalizer
{
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        return (new $type())->setProperties((array) $data);
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return is_a($type, WebFingerProperties::class, true);
    }

    /** @param WebFingerProperties $object */
    public function normalize(mixed $object, string $format = null, array $context = []): mixed
    {
        $properties = $object->getProperties();

        if ($context['skip_null_values'] ?? false) {
            $properties = array_filter($properties);
        }

        return $properties;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return is_a($data, WebFingerProperties::class);
    }
}
