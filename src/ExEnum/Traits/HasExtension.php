<?php

namespace ExEnum\Traits;

use ExEnum\Utils\Reflection;
use ExEnum\Exceptions\CaseNotFoundException;
use ExEnum\Exceptions\EnumNotFoundException;
use BadMethodCallException;

trait HasExtension
{
    public function __call(string $method_name, mixed $arguments): mixed
    {
        // when isXXX() is called
        if (str_starts_with($method_name, 'is')) {
            return $this->callToIs($method_name);
        }

        // when ifXXX() is called
        if (str_starts_with($method_name, 'if')) {
            return $this->callToIf($method_name, $arguments);
        }

        throw new BadMethodCallException(sprintf('Method %s::%s does not exist.', self::class, $method_name));
    }

    public static function fromName(string $name): self
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        throw new ValueError("{$name} is not a valid ContentType");
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

    private function callToIs(string $method_name): bool
    {
        $rm_is = substr($method_name, 2);
        in_array($rm_is, $this->names(), true) ?: throw new CaseNotFoundException($rm_is);

        return $this->name === $rm_is;
    }

    private function callToIf(string $method_name, mixed $arguments): mixed
    {
        $rm_if = substr($method_name, 2);
        in_array($rm_if, $this->names(), true) ?: throw new CaseNotFoundException($rm_if);

        $key = $this->name === $rm_if ? 'then' : 'else';

        return is_callable($arguments[$key] ?? null)
            ? call_user_func($arguments[$key])
            : null;
    }
}
