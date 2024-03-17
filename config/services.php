<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use AutoMapper\AutoMapperInterface;
use TurboSerializer\Serializer;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('turbo_serializer', Serializer::class)
        ->args([
            service('json_encoder.encoder'),
            service('json_encoder.decoder'),
            service(AutoMapperInterface::class),
            service('type_info.resolver.string'),
        ])
    ;
};
