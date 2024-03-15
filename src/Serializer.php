<?php

declare(strict_types=1);

namespace TurboSerializer;

use AutoMapper\AutoMapperInterface;
use Mtarld\JsonEncoderBundle\DecoderInterface;
use Mtarld\JsonEncoderBundle\EncoderInterface;
use Mtarld\JsonEncoderBundle\JsonDecoder;
use Mtarld\JsonEncoderBundle\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\TypeInfo\Type;
use Symfony\Component\TypeInfo\TypeResolver\StringTypeResolver;
use Symfony\Component\TypeInfo\Type\CollectionType;
use TurboSerializer\Exception\UnsupportedException;

final readonly class Serializer implements SerializerInterface
{
    public const TYPE = 'type';
    public const NORMALIZED_TYPE = 'normalized_type';

    /**
     * @param JsonEncoder $encoder
     * @param JsonDecoder $decoder
     */
    public function __construct(
        private EncoderInterface $encoder,
        private DecoderInterface $decoder,
        private AutoMapperInterface $autoMapper,
        private StringTypeResolver $typeResolver,
    ) {
    }

    /**
     * @param array<string, mixed>&array{type?: Type, normalized_type?: Type}
     */
    public function serialize(mixed $data, string $format, array $context = []): string
    {
        if ('json' !== $format) {
            throw new UnsupportedException(sprintf('"%s" format is not supported', $format));
        }

        $type = $context[static::TYPE] ?? (\is_object($data) ? Type::object($data::class) : Type::builtin(get_debug_type($data)));

        if (!array_key_exists(static::NORMALIZED_TYPE, $context)) {
            return (string) $this->encoder->encode($data, $type, $context);
        }

        $normalizedType = $context[static::NORMALIZED_TYPE];

        if ($normalizedType instanceof CollectionType) {
            $normalized = [];
            foreach ($data as $item) {
                $normalized[] = $this->autoMapper->map($item, (string) $normalizedType->getCollectionValueType(), $context);
            }

            return (string) $this->encoder->encode($normalized, $normalizedType, $context);
        }

        return (string) $this->encoder->encode($this->autoMapper->map($data, (string) $normalizedType, $context), $normalizedType, $context);
    }

    /**
     * @param array<string, mixed>&array{normalized_type?: Type}
     */
    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        if ('json' !== $format) {
            throw new UnsupportedException(sprintf('"%s" format is not supported', $format));
        }

        $type = $this->typeResolver->resolve($type);

        if (!array_key_exists(static::NORMALIZED_TYPE, $context)) {
            return $this->decoder->decode($data, $type, $context);
        }

        $normalizedType = $context[static::NORMALIZED_TYPE];
        $decoded = $this->decoder->decode($data, $normalizedType, $context);

        if ($normalizedType instanceof CollectionType) {
            $denormalized = [];
            foreach ($decoded as $item) {
                $denormalized[] = $this->autoMapper->map($item, (string) $normalizedType->getCollectionValueType(), $context);
            }

            return $denormalized;
        }

        return $this->autoMapper->map($decoded, (string) $type, $context);
    }
}
