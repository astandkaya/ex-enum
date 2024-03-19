<?php

namespace ExEnum\Traits;

use ExEnum\Utils\Reflection;

Trait HasExtension
{
    public function __call(string $method_name, $arguments): mixed
    {
        // when isXXX() is called
        if (str_starts_with($method_name, 'is')) {
            $rm_is = substr($method_name, 2);

            in_array($rm_is, $this->names(), true) ?: throw new Exception('undified case');

            return $this->name === $rm_is;
        }

        // when ifXXX() is called
        if (str_starts_with($method_name, 'if')) {
            $rm_if = substr($method_name, 2);

            in_array($rm_if, $this->names(), true) ?: throw new Exception('undified case');

            if ($this->name === $rm_if) {
                is_callable($arguments['then'] ?? null) ?: throw new Exception('callble is not defined');

                return call_user_func($arguments['then']);
            } else {
                is_callable($arguments['else'] ?? null) ?: throw new Exception('callble is not defined');

                return call_user_func($arguments['else']);
            }

            return $this->name === $rm_if;
        }

        throw new Exception('undified method');
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return Reflection::fetchData(self::class)[$this->name]['label'];
    }

    public static function labels(): array
    {
        return array_column(Reflection::fetchData(self::class), 'label');
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
