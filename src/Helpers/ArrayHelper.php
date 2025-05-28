<?php

namespace Marufnwu\Utils\Helpers;

/**
 * Array manipulation helper.
 *
 * @author marufnwu
 *
 * @since 1.0.0
 */
class ArrayHelper
{
    /**
     * Flatten a multi-dimensional array.
     */
    public static function flatten(array $array, float|int $depth = INF): array
    {
        $result = [];

        foreach ($array as $item) {
            if (is_array($item) && $depth > 0) {
                $result = array_merge($result, static::flatten($item, $depth - 1));
            } else {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * Get value from array using dot notation.
     */
    public static function get(array $array, string $key, $default = null)
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    /**
     * Set value in array using dot notation.
     */
    public static function set(array &$array, string $key, $value): array
    {
        $keys = explode('.', $key);
        $current = &$array;

        foreach ($keys as $k) {
            if (! is_array($current)) {
                $current = [];
            }
            $current = &$current[$k];
        }

        $current = $value;

        return $array;
    }

    /**
     * Remove value from array using dot notation.
     */
    public static function forget(array &$array, string $key): void
    {
        $keys = explode('.', $key);
        $current = &$array;

        foreach (array_slice($keys, 0, -1) as $k) {
            if (is_array($current) && array_key_exists($k, $current)) {
                $current = &$current[$k];
            } else {
                return;
            }
        }

        unset($current[end($keys)]);
    }

    /**
     * Check if array has key using dot notation.
     */
    public static function has(array $array, string $key): bool
    {
        foreach (explode('.', $key) as $segment) {
            if (is_array($array) && array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * Filter array by callback or value.
     */
    public static function where(array $array, callable|string $key, $operator = null, $value = null): array
    {
        if (is_callable($key)) {
            return array_filter($array, $key, ARRAY_FILTER_USE_BOTH);
        }

        if ($operator === null) {
            return array_filter($array, fn ($item) => is_array($item) ? static::get($item, $key) == $key : false
            );
        }

        return array_filter($array, function ($item) use ($key, $operator, $value) {
            $itemValue = is_array($item) ? static::get($item, $key) : $item;

            return match ($operator) {
                '=' => $itemValue == $value,
                '!=' => $itemValue != $value,
                '>' => $itemValue > $value,
                '>=' => $itemValue >= $value,
                '<' => $itemValue < $value,
                '<=' => $itemValue <= $value,
                'in' => in_array($itemValue, (array) $value),
                'not_in' => ! in_array($itemValue, (array) $value),
                default => false,
            };
        });
    }

    /**
     * Group array by key.
     */
    public static function groupBy(array $array, string|callable $key): array
    {
        $result = [];

        foreach ($array as $item) {
            $groupKey = is_callable($key) ? $key($item) : static::get($item, $key);
            $result[$groupKey][] = $item;
        }

        return $result;
    }

    /**
     * Pluck values from array.
     */
    public static function pluck(array $array, string $value, ?string $key = null): array
    {
        $result = [];

        foreach ($array as $item) {
            $itemValue = static::get($item, $value);

            if ($key === null) {
                $result[] = $itemValue;
            } else {
                $itemKey = static::get($item, $key);
                $result[$itemKey] = $itemValue;
            }
        }

        return $result;
    }

    /**
     * Sort array by key.
     */
    public static function sortBy(array $array, string|callable $key, string $direction = 'asc'): array
    {
        usort($array, function ($a, $b) use ($key, $direction) {
            $aValue = is_callable($key) ? $key($a) : static::get($a, $key);
            $bValue = is_callable($key) ? $key($b) : static::get($b, $key);

            $result = $aValue <=> $bValue;

            return $direction === 'desc' ? -$result : $result;
        });

        return $array;
    }

    /**
     * Check if array is associative.
     */
    public static function isAssoc(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * Convert array to object recursively.
     */
    public static function toObject(array $array): object
    {
        $object = new \stdClass;

        foreach ($array as $key => $value) {
            $object->$key = is_array($value) ? static::toObject($value) : $value;
        }

        return $object;
    }
}
