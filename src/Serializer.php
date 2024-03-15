<?php

declare(strict_types=1);

namespace TurboSerializer;

use AutoMapper\AutoMapperInterface;
use Mtarld\JsonEncoderBundle\DecoderInterface;
use Mtarld\JsonEncoderBundle\EncoderInterface;
use Mtarld\JsonEncoderBundle\JsonDecoder;
use Mtarld\JsonEncoderBundle\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\TypeInfo\TypeResolver\StringTypeResolver;
use TurboSerializer\Exception\UnsupportedException;

final readonly class Serializer implements SerializerInterface
{
    public const DTO_PROXY = 'turbo_dto_proxy';

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
     * @param array<string, mixed>&array{turbo_dto_proxy: string}
     */
    public function serialize(mixed $data, string $format, array $context = []): string
    {
        if ('json' !== $format) {
            throw new UnsupportedException(sprintf('"%s" format is not supported', $format));
        }

        if (array_key_exists(static::DTO_PROXY, $context)) {
            $data = $this->autoMapper->map($data, $context[static::DTO_PROXY]);

            return (string) $this->encoder->encode($data, config: $context + ['type' => $this->typeResolver->resolve($context[static::DTO_PROXY])]);
        }

        return (string) $this->encoder->encode($data, $context);
    }

    /**
     * @param array<string, mixed>&array{turbo_dto_proxy: string}
     */
    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        if ('json' !== $format) {
            throw new UnsupportedException(sprintf('"%s" format is not supported', $format));
        }

        if (array_key_exists(static::DTO_PROXY, $context)) {
            $proxy = $this->decoder->decode($data, $this->typeResolver->resolve($context[static::DTO_PROXY], $context));

            return $this->autoMapper->map($proxy, $type, $context);
        }

        return $this->decoder->decode($data, $this->typeResolver->resolve($type), $context);
    }
}
