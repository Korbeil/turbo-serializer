<?php

namespace TurboSerializer;

use AutoMapper\AutoMapperInterface;
use Mtarld\JsonEncoderBundle\DecoderInterface;
use Mtarld\JsonEncoderBundle\EncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\TypeInfo\Type;

readonly class Serializer implements SerializerInterface
{
    public function __construct(
        private EncoderInterface $encoder,
        private DecoderInterface $decoder,
        private AutoMapperInterface $autoMapper,
    ) {
    }

    public function serialize(mixed $data, string $format, array $context = []): string
    {
        /** @var array $array */
        $array = $this->autoMapper->map($data, 'array');

        return $this->encoder->encode($array);
    }

    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        /** @var array $decoded */
        $decoded = $this->decoder->decode($data, Type::array());

        return $this->autoMapper->map($decoded, $type);
    }
}