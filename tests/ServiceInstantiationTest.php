<?php

namespace TurboSerializer\Tests;

use Symfony\Component\Serializer\SerializerInterface;
use TurboSerializer\Serializer;

class ServiceInstantiationTest extends BundleSetUp
{
    public function testTurboSerializer(): void
    {
        static::bootKernel();
        $serializer = static::getContainer()->get(SerializerInterface::class);

        self::assertEquals(Serializer::class, $serializer::class);
    }
}