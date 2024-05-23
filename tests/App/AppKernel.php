<?php

namespace DummyApp;

use AutoMapper\Metadata\MapperMetadata;
use AutoMapper\Metadata\SourcePropertyMetadata;
use AutoMapper\Metadata\TargetPropertyMetadata;
use AutoMapper\Metadata\TypesMatching;
use AutoMapper\Symfony\Bundle\AutoMapperBundle;
use AutoMapper\Transformer\PropertyTransformer\PropertyTransformerInterface;
use AutoMapper\Transformer\PropertyTransformer\PropertyTransformerSupportInterface;
use Mtarld\JsonEncoderBundle\JsonEncoderBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Serializer\SerializerInterface;
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

class TestService
{
    public function __construct(
        public SerializerInterface $turboSerializer,
    ) {
    }

}

class NurseryTransformer implements PropertyTransformerInterface, PropertyTransformerSupportInterface
{
    public function supports(TypesMatching $types, SourcePropertyMetadata $source, TargetPropertyMetadata $target, MapperMetadata $mapperMetadata): bool
    {
        return
            NormalizedNursery::class === $mapperMetadata->source &&
            Nursery::class === $target &&
            'total' === $source->property;
    }

    /**
     * @param NormalizedNursery $source
     */
    public function transform(mixed $value, object|array $source, array $context): mixed
    {
        return \count($source->cats);
    }
}
