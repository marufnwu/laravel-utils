<?php

namespace Marufnwu\Utils\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * StringHelper Facade.
 *
 * @method static string camel(string $string)
 * @method static string studly(string $string)
 * @method static string snake(string $string, string $delimiter = '_')
 * @method static string kebab(string $string)
 * @method static string title(string $string)
 * @method static string limit(string $string, int $limit = 100, string $end = '...')
 * @method static string words(string $string, int $words = 100, string $end = '...')
 * @method static int length(string $string, string $encoding = null)
 * @method static string slug(string $title, string $separator = '-', string $language = 'en')
 * @method static string ascii(string $value, string $language = 'en')
 * @method static string random(int $length = 16)
 * @method static bool startsWith(string $haystack, string|array $needles)
 * @method static bool endsWith(string $haystack, string|array $needles)
 * @method static bool contains(string $haystack, string|array $needles)
 * @method static string replaceFirst(string $search, string $replace, string $subject)
 * @method static string replaceLast(string $search, string $replace, string $subject)
 * @method static string mask(string $string, string $character, int $index, int $length = null)
 *
 * @see \Marufnwu\Utils\Helpers\StringHelper
 */
class StringHelper extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'string-helper';
    }
}
