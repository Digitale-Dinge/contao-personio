<?php

declare(strict_types=1);

namespace InspiredMinds\ContaoPersonio\Serializer;

use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

// https://github.com/symfony/symfony/discussions/58898
class EmptyArrayDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize(mixed $data, string $type, string|null $format = null, array $context = []): mixed
    {
        if (\in_array(trim((string) $data), ['', '0'], true)) {
            return null;
        }

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization(mixed $data, string $type, string|null $format = null, array $context = []): bool
    {
        return \is_string($data)
            && \in_array(trim($data), ['', '0'], true)
            && \array_key_exists(self::class, $context)
            && $type === $context[self::class];
    }

    public function getSupportedTypes(string|null $format): array
    {
        return ['*' => false];
    }
}
