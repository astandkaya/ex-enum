<?php

namespace ExEnum\Traits;

use ExEnum\Utils\Reflection;
use ExEnum\Exceptions\CaseNotFoundException;
use ExEnum\Exceptions\EnumNotFoundException;
use BadMethodCallException;
use ValueError;

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

        throw new BadMethodCallException(sprintf('Method %s::%s does not exist.', static::class, $method_name));
    }

    public static function fromName(string $name): static
    {
        return static::tryFromName($name) ?? throw new ValueError("{$name} is not a valid ContentType");
    }

    public static function tryFromName(string $name): ?static
    {
        $value = array_combine(static::names(), static::values())[$name] ?? '';

        return static::tryFrom($value);
    }

    public static function names(): array
    {
        return array_column(static::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }

    public function label(): string
    {
        return Reflection::fetchData(static::class)['cases'][$this->name]['label'];
    }

    public static function labels(): array
    {
        return array_column(Reflection::fetchData(static::class)['cases'], 'label');
    }

    public static function casesOnly(array $tag_names): array
    {
        $data = Reflection::fetchData(static::class)['tags'];

        // fillter
        $cases = array_intersect_key($data, array_flip($tag_names));
        // flatten
        $cases = array_merge(...array_values($cases));

        return $cases;
    }

    public static function casesExcept(array $tag_names): array
    {
        $cases = array_udiff(
            static::cases(),
            static::casesOnly($tag_names),
            fn ($c, $e) => $c->name <=> $e->name,
        );

        return array_values($cases);
    }

    public function hasTag(string $tag_name): bool
    {
        return $this->hasTags([$tag_name]);
    }

    public function hasTags(array $tag_names): bool
    {
        return empty(
            array_diff($tag_names, $this->tags())
        );
    }

    public function tags(): array
    {
        return Reflection::fetchData(static::class)['cases'][$this->name]['tags'];
    }

    public static function sortBy(): array
    {
        $data = Reflection::fetchData(static::class)['cases'];

        usort($data, fn ($a, $b) => $a['order'] <=> $b['order']);

        return array_map(
            fn ($datum) => $datum['case'],
            $data,
        );
    }

    public static function sortByAsc(): array
    {
        return static::sortBy();
    }

    public static function sortByDesc(): array
    {
        return array_reverse(static::sortBy());
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
