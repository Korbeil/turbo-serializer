<?php

namespace DummyApp;

use AutoMapper\Bundle\AutoMapperBundle;
use Mtarld\JsonEncoderBundle\DependencyInjection\JsonEncodablePass;
use Mtarld\JsonEncoderBundle\DependencyInjection\JsonEncoderPass;
use Mtarld\JsonEncoderBundle\DependencyInjection\RuntimeServicesPass;
use Mtarld\JsonEncoderBundle\JsonEncoderBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use TurboSerializer\TurboSerializerBundle;

class AppKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new TurboSerializerBundle(),
            new AutoMapperBundle(),
            new JsonEncoderBundle(),
        ];
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config.yaml');
    }

    public function getProjectDir(): string
    {
        return __DIR__ . '/..';
    }
}
