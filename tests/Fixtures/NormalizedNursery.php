<?php

namespace TurboSerializer\Tests\Fixtures;

readonly class NormalizedNursery
{
    public string $name;
    /** @var list<Cat> */
    public array $cats;
}
