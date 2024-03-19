<?php

namespace ExEnum\Traits;

use ExEnum\Utils\Reflection;

Trait HasExtension
{
    public function label(): string
    {
        return Reflection::fetchData(self::class)[$this->name]['label'];
    }

    public static function casesOnly(array $tag_names): array
    {
        $data = Reflection::fetchData(self::class);

        // fillter
        $cases = array_intersect_key($data['tags'], array_flip($tag_names));
        // flatten
        $cases = array_merge(...array_values($cases));

        return $cases;
    }

    public static function casesExcept(array $tag_names): array
    {
        $data = Reflection::fetchData(self::class);

        // except cases
        $excepts = self::casesOnly($tag_names);
        // all cases
        $cases = array_merge(...array_values($data['tags']));
        // fillter
        $cases = array_udiff($cases, $excepts, fn ($c, $e) => $c->name <=> $e->name);

        return $cases;
    }
}
