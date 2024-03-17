<?php

declare(strict_types=1);

namespace TurboSerializer;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\Serializer\SerializerInterface;

final class TurboSerializerBundle extends AbstractBundle
{
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.php');
        $builder->registerAliasForArgument('turbo_serializer', SerializerInterface::class, 'turbo.serializer');
    }
}
