<?php

namespace TurboSerializer\Tests;

use TurboSerializer\Serializer;

class ServiceInstantiationTest extends BundleSetUp
{
    public function testTurboSerializer(): void
    {
        self::assertEquals(Serializer::class, $this->serializer::class);
    }
}
