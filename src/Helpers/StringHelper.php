<?php

namespace Marufnwu\Utils\Helpers;

/**
 * String manipulation helper.
 *
 * @author marufnwu
 *
 * @since 1.0.0
 */
class StringHelper
{
    /**
     * Convert string to camelCase.
     */
    public static function camel(string $string): string
    {
        return lcfirst(static::studly($string));
    }

    /**
     * Convert string to StudlyCase.
     */
    public static function studly(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $string)));
    }

    /**
     * Convert string to snake_case.
     */
    public static function snake(string $string, string $delimiter = '_'): string
    {
        if (! ctype_lower($string)) {
            $string = preg_replace('/\s+/u', '', ucwords($string));
            $string = preg_replace('/(.)(?=[A-Z])/u', '$1'.$delimiter, $string);
            $string = mb_strtolower($string, 'UTF-8');
        }

        return $string;
    }

    /**
     * Convert string to kebab-case.
     */
    public static function kebab(string $string): string
    {
        return static::snake($string, '-');
    }

    /**
     * Convert string to Title Case.
     */
    public static function title(string $string): string
    {
        return mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * Limit string length.
     */
    public static function limit(string $string, int $limit = 100, string $end = '...'): string
    {
        if (mb_strwidth($string, 'UTF-8') <= $limit) {
            return $string;
        }

        return rtrim(mb_strimwidth($string, 0, $limit, '', 'UTF-8')).$end;
    }

    /**
     * Limit string by words.
     */
    public static function words(string $string, int $words = 100, string $end = '...'): string
    {
        preg_match('/^\s*+(?:\S++\s*+){1,'.$words.'}/u', $string, $matches);

        if (! isset($matches[0]) || static::length($string) === static::length($matches[0])) {
            return $string;
        }

        return rtrim($matches[0]).$end;
    }

    /**
     * Get string length.
     */
    public static function length(string $string, ?string $encoding = null): int
    {
        return mb_strlen($string, $encoding);
    }

    /**
     * Convert string to slug.
     */
    public static function slug(string $title, string $separator = '-', string $language = 'en'): string
    {
        $title = $language ? static::ascii($title, $language) : $title;

        // Convert all dashes/underscores into separator
        $flip = $separator === '-' ? '_' : '-';
        $title = preg_replace('!['.preg_quote($flip).']+!u', $separator, $title);

        // Replace @ with the word 'at'
        $title = str_replace('@', $separator.'at'.$separator, $title);

        // Remove all characters that are not the separator, letters, numbers, or whitespace
        $title = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($title, 'UTF-8'));

        // Replace all separator characters and whitespace by a single separator
        $title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);

        return trim($title, $separator);
    }

    /**
     * Transliterate a UTF-8 value to ASCII.
     */
    public static function ascii(string $value, string $language = 'en'): string
    {
        $languageSpecific = static::languageSpecificCharsArray($language);

        if (! is_null($languageSpecific)) {
            $value = str_replace($languageSpecific[0], $languageSpecific[1], $value);
        }

        return preg_replace('/[^\x20-\x7E]/u', '', $value);
    }

    /**
     * Get language specific character replacements.
     */
    protected static function languageSpecificCharsArray(string $language): ?array
    {
        static $languageSpecific = [
            'bg' => [
                ['х', 'Х', 'щ', 'Щ', 'ъ', 'Ъ', 'ь', 'Ь'],
                ['h', 'H', 'sht', 'SHT', 'a', 'А', 'y', 'Y'],
            ],
            'de' => [
                ['ä',  'ö',  'ü',  'Ä',  'Ö',  'Ü',  'ß'],
                ['ae', 'oe', 'ue', 'AE', 'OE', 'UE', 'ss'],
            ],
        ];

        return $languageSpecific[$language] ?? null;
    }

    /**
     * Generate a random string.
     */
    public static function random(int $length = 16): string
    {
        $string = '';

        while (($len = strlen($string)) < $length) {
            $size = $length - $len;
            $bytes = random_bytes($size);
            $string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
        }

        return $string;
    }

    /**
     * Check if string starts with given value.
     */
    public static function startsWith(string $haystack, string|array $needles): bool
    {
        foreach ((array) $needles as $needle) {
            if ((string) $needle !== '' && str_starts_with($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if string ends with given value.
     */
    public static function endsWith(string $haystack, string|array $needles): bool
    {
        foreach ((array) $needles as $needle) {
            if ((string) $needle !== '' && str_ends_with($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if string contains given value.
     */
    public static function contains(string $haystack, string|array $needles): bool
    {
        foreach ((array) $needles as $needle) {
            if ((string) $needle !== '' && str_contains($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Replace first occurrence of string.
     */
    public static function replaceFirst(string $search, string $replace, string $subject): string
    {
        if ($search === '') {
            return $subject;
        }

        $position = strpos($subject, $search);

        if ($position !== false) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }

        return $subject;
    }

    /**
     * Replace last occurrence of string.
     */
    public static function replaceLast(string $search, string $replace, string $subject): string
    {
        if ($search === '') {
            return $subject;
        }

        $position = strrpos($subject, $search);

        if ($position !== false) {
            return substr_replace($subject, $replace, $position, strlen($search));
        }

        return $subject;
    }

    /**
     * Mask string with character.
     */
    public static function mask(string $string, string $character, int $index, ?int $length = null): string
    {
        if ($character === '') {
            return $string;
        }

        $segment = mb_substr($string, $index, $length, 'UTF-8');

        if ($segment === '') {
            return $string;
        }

        $strlen = mb_strlen($string, 'UTF-8');
        $startIndex = $index;

        if ($index < 0) {
            $startIndex = $index < -$strlen ? 0 : $strlen + $index;
        }

        $start = mb_substr($string, 0, $startIndex, 'UTF-8');
        $segmentLen = mb_strlen($segment, 'UTF-8');
        $end = mb_substr($string, $startIndex + $segmentLen);

        return $start.str_repeat(mb_substr($character, 0, 1, 'UTF-8'), $segmentLen).$end;
    }
}
