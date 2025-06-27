<?php

namespace ExEnum\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Extension
{
    public function __construct(
        public readonly string $label,
        public readonly int $order,
        public readonly array $tags,
    ) {
    }
}
