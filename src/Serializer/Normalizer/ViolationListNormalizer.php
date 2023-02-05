<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationListNormalizer extends AbstractNormalizer
{
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        return null;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return false;
    }

    /** @param ConstraintViolationListInterface $object */
    public function normalize(mixed $object, string $format = null, array $context = []): mixed
    {
        $output = [
            'message' => 'Invalid data provided.',
            'errors' => [],
        ];
        /** @var ConstraintViolationInterface $error */
        foreach ($object as $error) {
            $output['errors'][] = [
                'property' => $error->getPropertyPath(),
                'error' => (string) $error->getMessage(),
            ];
        }

        return $output;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return is_a($data, ConstraintViolationListInterface::class);
    }
}
