<?php

namespace TurboSerializer\Tests;

use Symfony\Component\Serializer\SerializerInterface;
use TurboSerializer\Serializer;
use TurboSerializer\Tests\Fixtures\Cat;
use TurboSerializer\Tests\Fixtures\NormalizedNursery;
use TurboSerializer\Tests\Fixtures\Nursery;

class SerializerTest extends BundleSetUp
{
    public const JSON = '{"name":"Holicats","cats":[{"name":"Marion"},{"name":"Benjamin"},{"name":"Kit"}]}';

    public function testDeserialize(): void
    {
        static::bootKernel();
        /** @var SerializerInterface $serializer */
        $serializer = static::getContainer()->get(SerializerInterface::class);

        /** @var Nursery $nursery */
        $nursery = $serializer->deserialize(
            '{"name":"Holicats","cats":[{"name":"Marion"},{"name":"Benjamin"},{"name":"Kit"}]}',
            Nursery::class,
            'json'
        );

        $this->assertInstanceOf(Nursery::class, $nursery);
        $this->assertEquals('Holicats', $nursery->name);
        $this->assertIsArray($nursery->cats);
        foreach ($nursery->cats as $cat) {
            $this->assertInstanceOf(Cat::class, $cat);
            $this->assertTrue(\in_array($cat->name, ['Marion', 'Benjamin', 'Kit'], true));
        }
        $this->assertEquals(0, $nursery->total);
    }

    public function testDeserializeWithProxy(): void
    {
        static::bootKernel();
        /** @var SerializerInterface $serializer */
        $serializer = static::getContainer()->get(SerializerInterface::class);

        /** @var Nursery $nursery */
        $nursery = $serializer->deserialize(
            '{"name":"Holicats","cats":[{"name":"Marion"},{"name":"Benjamin"},{"name":"Kit"}]}',
            Nursery::class,
            'json',
            [Serializer::DTO_PROXY => NormalizedNursery::class]
        );

        $this->assertInstanceOf(Nursery::class, $nursery);
        $this->assertEquals('Holicats', $nursery->name);
        $this->assertIsArray($nursery->cats);
        foreach ($nursery->cats as $cat) {
            $this->assertInstanceOf(Cat::class, $cat);
            $this->assertTrue(\in_array($cat->name, ['Marion', 'Benjamin', 'Kit'], true));
        }
        $this->assertEquals(3, $nursery->total);
    }

    public function testSerialize(): void
    {
        static::bootKernel();
        /** @var SerializerInterface $serializer */
        $serializer = static::getContainer()->get(SerializerInterface::class);

        $nursery = new Nursery();
        $nursery->name = 'Holicats';
        $nursery->cats = [];
        $cat = new Cat();
        $cat->name = 'Marion';
        $nursery->cats[] = $cat;
        $cat = new Cat();
        $cat->name = 'Benjamin';
        $nursery->cats[] = $cat;
        $cat = new Cat();
        $cat->name = 'Kit';
        $nursery->cats[] = $cat;

        $nursery = $serializer->serialize($nursery, 'json');

        $this->assertIsString($nursery);
        $this->assertEquals(
            '{"name":"Holicats","cats":[{"name":"Marion"},{"name":"Benjamin"},{"name":"Kit"}],"total":0}',
            $nursery
        );
    }
}