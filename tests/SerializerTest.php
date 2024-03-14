<?php

namespace TurboSerializer\Tests;

use Symfony\Component\Serializer\SerializerInterface;
use TurboSerializer\Tests\Fixtures\Nursery;

class SerializerTest extends BundleSetUp
{
    public function testDeserialize(): void
    {
        static::bootKernel();
        /** @var SerializerInterface $serializer */
        $serializer = static::getContainer()->get(SerializerInterface::class);

        $jsonSource = '{"name":"Holicats","cats":[{"name":"Marion"},{"name":"Benjamin"},{"name":"Kit"}]}';

        /** @var Nursery $nursery */
        $nursery = $serializer->deserialize($jsonSource, Nursery::class, 'json');

        $this->assertInstanceOf(Nursery::class, $nursery);
        var_dump($nursery);
    }
}