<?php

namespace TurboSerializer\Tests\Fixtures;

use Doctrine\Common\Collections\Collection;

readonly class Nursery
{
    /**
     * @param Collection<Cat> $cats
     */
    public function __construct(
        public string $name,
        public Collection $cats,
        public int $total = 0,
    ) {
    }
}
