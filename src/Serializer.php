<?php

namespace TurboSerializer;

use AutoMapper\AutoMapperInterface;
use Mtarld\JsonEncoderBundle\DecoderInterface;
use Mtarld\JsonEncoderBundle\EncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\TypeInfo\Type;

readonly class Serializer implements SerializerInterface
{
    public const DTO_PROXY = 'turbo_dto_proxy';

    public function __construct(
        private EncoderInterface $encoder,
        private DecoderInterface $decoder,
        private AutoMapperInterface $autoMapper,
    ) {
    }

    public function serialize(mixed $data, string $format, array $context = []): string
    {
        if (array_key_exists(static::DTO_PROXY, $context)) {
            $data = $this->autoMapper->map($data, $context[static::DTO_PROXY]);
        }

        return $this->encoder->encode($data);
    }

    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        if (array_key_exists(static::DTO_PROXY, $context)) {
            /** @var T $proxy */
            $proxy = $this->decoder->decode($data, Type::object($context[static::DTO_PROXY]));

            return $this->autoMapper->map($proxy, $type, $context);
        }

        return $this->decoder->decode($data, Type::object($type));
    }
}