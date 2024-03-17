<?php

namespace TurboSerializer\Tests;

use DummyApp\TestService;
use TurboSerializer\Serializer;

class ServiceInstantiationTest extends BundleSetUp
{
    public function testTurboSerializer(): void
    {
        self::assertEquals(Serializer::class, $this->serializer::class);
    }

    public function testTurboSerializerAsArgument(): void
    {
        /** @var TestService $testService */
        $testService = static::getContainer()->get(TestService::class);

        self::assertEquals(Serializer::class, $testService->turboSerializer::class);
    }
}
