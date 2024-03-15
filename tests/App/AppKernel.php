<?php

namespace DummyApp;

use AutoMapper\Bundle\AutoMapperBundle;
use AutoMapper\Transformer\CustomTransformer\CustomPropertyTransformerInterface;
use Mtarld\JsonEncoderBundle\JsonEncoderBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use TurboSerializer\Tests\Fixtures\NormalizedNursery;
use TurboSerializer\Tests\Fixtures\Nursery;
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

class NurseryTransformer implements CustomPropertyTransformerInterface
{
    public function supports(string $source, string $target, string $propertyName): bool
    {
        return
            NormalizedNursery::class === $source &&
            Nursery::class === $target &&
            'total' === $propertyName;
    }

    public function transform(object|array $source): mixed
    {
        return \count($source->cats);
    }
}
