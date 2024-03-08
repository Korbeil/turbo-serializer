<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use AutoMapper\AutoMapperInterface;
use Mtarld\JsonEncoderBundle\DecoderInterface;
use Mtarld\JsonEncoderBundle\EncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use TurboSerializer\Serializer;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('serializer', Serializer::class)
        ->args([
            service(EncoderInterface::class),
            service(DecoderInterface::class),
            service(AutoMapperInterface::class),
        ])
        ->alias(SerializerInterface::class, 'serializer')
    ;
};