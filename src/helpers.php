<?php

use Marufnwu\Utils\Helpers\ArrayHelper;
use Marufnwu\Utils\Helpers\StringHelper;
use Marufnwu\Utils\Pipeline;

if (! function_exists('pipeline_success')) {
    /**
     * Create a success pipeline response.
     */
    function pipeline_success($data = null, string $message = 'Operation completed successfully', int $status = 200, array $meta = [])
    {
        return Pipeline::success($data, $message, $status, $meta);
    }
}

if (! function_exists('pipeline_error')) {
    /**
     * Create an error pipeline response.
     */
    function pipeline_error(string $message = 'An error occurred', int $status = 400, $data = [], ?int $errorCode = null, array $meta = [])
    {
        return Pipeline::error($message, $status, $data, $errorCode, $meta);
    }
}

if (! function_exists('pipeline_validation')) {
    /**
     * Create a validation error pipeline response.
     */
    function pipeline_validation($errors, string $message = 'Validation failed', int $status = 422, ?int $errorCode = 1001)
    {
        return Pipeline::validationError($errors, $message, $status, $errorCode);
    }
}

if (! function_exists('array_get_dot')) {
    /**
     * Get value from array using dot notation.
     */
    function array_get_dot(array $array, string $key, $default = null)
    {
        return ArrayHelper::get($array, $key, $default);
    }
}

if (! function_exists('array_set_dot')) {
    /**
     * Set value in array using dot notation.
     */
    function array_set_dot(array &$array, string $key, $value): array
    {
        return ArrayHelper::set($array, $key, $value);
    }
}

if (! function_exists('str_limit_words')) {
    /**
     * Limit string by words.
     */
    function str_limit_words(string $string, int $words = 100, string $end = '...'): string
    {
        return StringHelper::words($string, $words, $end);
    }
}

if (! function_exists('str_mask')) {
    /**
     * Mask string with character.
     */
    function str_mask(string $string, string $character, int $index, ?int $length = null): string
    {
        return StringHelper::mask($string, $character, $index, $length);
    }
}
