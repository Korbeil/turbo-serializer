<?php

namespace TurboSerializer\Tests\Fixtures;

class Nursery
{
    public string $name;

    /** @var list<Cat> $cats */
    public array $cats;

    public int $total = 0;
}
