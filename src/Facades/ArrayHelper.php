<?php

namespace Marufnwu\Utils\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * ArrayHelper Facade.
 *
 * @method static array flatten(array $array, int $depth = INF)
 * @method static mixed get(array $array, string $key, $default = null)
 * @method static array set(array &$array, string $key, $value)
 * @method static void forget(array &$array, string $key)
 * @method static bool has(array $array, string $key)
 * @method static array where(array $array, callable|string $key, $operator = null, $value = null)
 * @method static array groupBy(array $array, string|callable $key)
 * @method static array pluck(array $array, string $value, string $key = null)
 * @method static array sortBy(array $array, string|callable $key, string $direction = 'asc')
 * @method static bool isAssoc(array $array)
 * @method static object toObject(array $array)
 *
 * @see \Marufnwu\Utils\Helpers\ArrayHelper
 */
class ArrayHelper extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'array-helper';
    }
}
