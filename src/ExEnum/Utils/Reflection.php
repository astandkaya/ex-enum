<?php

namespace ExEnum\Utils;

use ExEnum\Exceptions\EnumNotFoundException;
use ReflectionEnum;

class Reflection
{
    private static array $cache = [];

    public static function fetchData(string $enum_name): array
    {
        enum_exists($enum_name) ?: throw new EnumNotFoundException($enum_name);

        if (isset(self::$cache[$enum_name])) {
            return self::$cache[$enum_name];
        }

        $reflection = new ReflectionEnum($enum_name);
        $cases = $reflection->getCases();

        $data = [];
        foreach($cases as $case) {
            foreach ($case->getAttributes() as $attribute) {
                $data[$case->getName()] = [
                    'name' => $case->getName(),
                    'case' => $case->getValue(),
                    ...$attribute->getArguments(),
                ];

                foreach ($attribute->getArguments()['tags'] as $tag_name) {
                    $data['tags'][$tag_name][] = $case->getValue();
                }
            }
        }

        return self::$cache[$enum_name] = $data;
    }
}
