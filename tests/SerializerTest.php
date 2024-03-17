<?php

namespace TurboSerializer\Tests;

use Symfony\Component\TypeInfo\Type;
use TurboSerializer\Serializer;
use TurboSerializer\Tests\Fixtures\Cat;
use TurboSerializer\Tests\Fixtures\NormalizedNursery;
use TurboSerializer\Tests\Fixtures\Nursery;

final class SerializerTest extends BundleSetUp
{
    private const JSON = '{"name":"Holicats","cats":[{"name":"Marion"},{"name":"Benjamin"},{"name":"Kit"}]}';

    public function testDeserialize(): void
    {
        /** @var Nursery $nursery */
        $nursery = $this->serializer->deserialize(self::JSON, Nursery::class, 'json');

        $this->assertInstanceOf(Nursery::class, $nursery);

        $this->assertSame('Holicats', $nursery->name);

        $this->assertIsArray($nursery->cats);
        $this->assertContainsOnly(Cat::class, $nursery->cats);
        $this->assertSame(['Marion', 'Benjamin', 'Kit'], array_map(fn (Cat $c): string => $c->name, $nursery->cats));
        $this->assertSame(0, $nursery->total);
    }

    public function testDeserializeWithProxy(): void
    {
        /** @var Nursery $nursery */
        $nursery = $this->serializer->deserialize(self::JSON, Nursery::class, 'json', [
            Serializer::NORMALIZED_TYPE => Type::object(NormalizedNursery::class),
        ]);

        $this->assertInstanceOf(Nursery::class, $nursery);

        $this->assertEquals('Holicats', $nursery->name);

        $this->assertIsArray($nursery->cats);
        $this->assertContainsOnly(Cat::class, $nursery->cats);
        $this->assertSame(['Marion', 'Benjamin', 'Kit'], array_map(fn (Cat $c): string => $c->name, $nursery->cats));
        $this->assertSame(3, $nursery->total);
    }

    public function testSerialize(): void
    {
        $nursery = new NormalizedNursery();
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

        $nursery = $this->serializer->serialize($nursery, 'json');

        $this->assertSame(self::JSON, $nursery);
    }

    public function testSerializeWithProxy(): void
    {
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

        $nursery = $this->serializer->serialize($nursery, 'json', [
            Serializer::NORMALIZED_TYPE => Type::object(NormalizedNursery::class),
        ]);

        $this->assertSame(self::JSON, $nursery);
    }
}
