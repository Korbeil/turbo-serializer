<?php

namespace TurboSerializer\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\SerializerInterface;

abstract class BundleSetUp extends KernelTestCase
{
    protected SerializerInterface $serializer;

    protected function setUp(): void
    {
        static::$class = null;
        $_SERVER['KERNEL_DIR'] = __DIR__ . '/App';
        $_SERVER['KERNEL_CLASS'] = 'DummyApp\AppKernel';

        (new Filesystem())->remove(__DIR__ . '/var/cache/test');

        static::bootKernel();

        $this->serializer = static::getContainer()->get('turbo_serializer.alias');
    }
}
