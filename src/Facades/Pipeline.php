<?php

namespace Marufnwu\Utils\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Pipeline Facade.
 *
 * @method static \Marufnwu\Utils\Pipeline success($data = null, string $message = 'Operation completed successfully', int $status = 200, array $meta = [])
 * @method static \Marufnwu\Utils\Pipeline error(string $message = 'An error occurred', int $status = 400, $data = [], ?int $errorCode = null, array $meta = [])
 * @method static \Marufnwu\Utils\Pipeline validationError($errors, string $message = 'Validation failed', int $status = 422, ?int $errorCode = 1001)
 * @method static \Marufnwu\Utils\Pipeline notFound(string $message = 'Resource not found', ?int $errorCode = 1002)
 * @method static \Marufnwu\Utils\Pipeline unauthorized(string $message = 'Unauthorized access', ?int $errorCode = 1003)
 * @method static \Marufnwu\Utils\Pipeline forbidden(string $message = 'Access forbidden', ?int $errorCode = 1004)
 * @method static \Marufnwu\Utils\Pipeline serverError(string $message = 'Internal server error', $data = [], ?int $errorCode = 1005)
 *
 * @see \Marufnwu\Utils\Pipeline
 */
class Pipeline extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'pipeline';
    }
}
