<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use AutoMapper\AutoMapperInterface;
use Symfony\Component\Serializer\SerializerInterface;
use TurboSerializer\Serializer;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('.turbo.serializer', Serializer::class)
        ->args([
            service('json_encoder.encoder'),
            service('json_encoder.decoder'),
            service(AutoMapperInterface::class),
            service('type_info.resolver.string'),
        ])
        ->public()
        ->alias(SerializerInterface::class, '.turbo.serializer')
    ;
};