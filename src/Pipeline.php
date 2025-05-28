<?php

namespace Marufnwu\Utils;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

/**
 * Pipeline for standardizing responses across different output formats.
 *
 * @template T The type of the data carried by the pipeline
 *
 * @author marufnwu
 *
 * @version 1.0.0
 *
 * @since 2025-05-28
 */
class Pipeline implements \JsonSerializable, Arrayable, Jsonable
{
    /** @var T|null */
    public $data;

    /** @var string */
    public $message;

    /** @var int */
    public $status;

    /** @var bool */
    public $success;

    /** @var int|null */
    public $errorCode;

    /** @var array */
    public $meta;

    /** @var array */
    public $headers;

    /** @var string|null */
    public $requestId;

    /**
     * Pipeline constructor.
     *
     * @param  T|null  $data  The data to be returned
     * @param  string  $message  The message to be returned
     * @param  int  $status  HTTP status code
     * @param  bool  $success  Whether the operation was successful
     * @param  int|null  $errorCode  Optional error code for client-side handling
     * @param  array  $meta  Additional metadata
     * @param  array  $headers  HTTP headers
     */
    public function __construct(
        $data = null,
        string $message = 'Success',
        int $status = 200,
        bool $success = true,
        ?int $errorCode = null,
        array $meta = [],
        array $headers = []
    ) {
        $this->data = $data;
        $this->message = $message ?: config('utils.default_success_message', 'Operation completed successfully');
        $this->status = $status;
        $this->success = $success;
        $this->errorCode = $errorCode;
        $this->meta = $meta;
        $this->headers = $headers;
        $this->requestId = $this->generateRequestId();
    }

    /**
     * Generate unique request ID.
     */
    protected function generateRequestId(): string
    {
        return config('utils.include_request_id', true) ? \Str::uuid()->toString() : '';
    }

    /**
     * Static method to initialize the pipeline with a success response.
     *
     * @param  T|null  $data  The data to be returned
     * @param  string  $message  Success message
     * @param  int  $status  HTTP status code
     * @param  array  $meta  Additional metadata
     * @return static
     */
    public static function success($data = null, string $message = 'Operation completed successfully', int $status = 200, array $meta = []): self
    {
        return new self($data, $message, $status, true, null, $meta);
    }

    /**
     * Static method to initialize the pipeline with an error response.
     *
     * @param  string  $message  Error message
     * @param  int  $status  HTTP status code
     * @param  mixed  $data  Additional error data
     * @param  int|null  $errorCode  Optional error code for client-side handling
     * @param  array  $meta  Additional metadata
     * @return static
     */
    public static function error(
        string $message = 'An error occurred',
        int $status = 400,
        $data = [],
        ?int $errorCode = null,
        array $meta = []
    ): self {
        return new self($data, $message, $status, false, $errorCode, $meta);
    }

    /**
     * Static method to initialize the pipeline with a validation error response.
     *
     * @param  mixed  $errors  Validation errors
     * @param  string  $message  Error message
     * @param  int  $status  HTTP status code
     * @param  int|null  $errorCode  Optional error code for client-side handling
     * @return static
     */
    public static function validationError(
        $errors,
        string $message = 'Validation failed',
        int $status = 422,
        ?int $errorCode = 1001
    ): self {
        return new self($errors, $message, $status, false, $errorCode);
    }

    /**
     * Static method for not found responses.
     *
     * @return static
     */
    public static function notFound(string $message = 'Resource not found', ?int $errorCode = 1002): self
    {
        return new self([], $message, 404, false, $errorCode);
    }

    /**
     * Static method for unauthorized responses.
     *
     * @return static
     */
    public static function unauthorized(string $message = 'Unauthorized access', ?int $errorCode = 1003): self
    {
        return new self([], $message, 401, false, $errorCode);
    }

    /**
     * Static method for forbidden responses.
     *
     * @return static
     */
    public static function forbidden(string $message = 'Access forbidden', ?int $errorCode = 1004): self
    {
        return new self([], $message, 403, false, $errorCode);
    }

    /**
     * Static method for server error responses.
     *
     * @param  mixed  $data
     * @return static
     */
    public static function serverError(
        string $message = 'Internal server error',
        $data = [],
        ?int $errorCode = 1005
    ): self {
        return new self($data, $message, 500, false, $errorCode);
    }

    /**
     * Check if the pipeline represents a successful operation.
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * Check if the pipeline represents a failed operation.
     */
    public function isError(): bool
    {
        return ! $this->success;
    }

    /**
     * Get the data from the pipeline.
     *
     * @return T|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Add or modify data in the pipeline.
     *
     * @param  T  $data
     * @return $this
     */
    public function withData($data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Modify the message.
     */
    public function withMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Modify the status code.
     */
    public function withStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Set the error code.
     */
    public function withErrorCode(?int $errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * Add metadata to the pipeline.
     */
    public function withMeta(array $meta): self
    {
        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }

    /**
     * Add headers to the pipeline.
     */
    public function withHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * Convert the object to a JsonResponse.
     */
    public function toApiResponse(): JsonResponse
    {
        $response = [
            'success' => $this->success,
            'message' => $this->message,
        ];

        if (config('utils.include_timestamp', true)) {
            $response['timestamp'] = now()->toISOString();
        }

        if (config('utils.include_request_id', true) && $this->requestId) {
            $response['request_id'] = $this->requestId;
        }

        if ($this->success) {
            $response['data'] = $this->data;
        } else {
            $response['errors'] = $this->data;
            if ($this->errorCode) {
                $response['error_code'] = $this->errorCode;
            }
        }

        if (! empty($this->meta)) {
            $response['meta'] = $this->meta;
        }

        return response()->json($response, $this->status, $this->headers);
    }

    /**
     * Convert the pipeline to a view response.
     */
    public function toViewResponse(string $view, array $additionalData = [])
    {
        $viewData = array_merge([
            'pipeline' => $this,
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data,
            'meta' => $this->meta,
            'request_id' => $this->requestId,
        ], $additionalData);

        if (! view()->exists($view)) {
            return response()->view('errors.404', [], 404);
        }

        return view($view, $viewData);
    }

    /**
     * Convert to redirect response with flash data.
     */
    public function toRedirectResponse(string $route, array $parameters = []): RedirectResponse
    {
        $redirect = redirect()->route($route, $parameters);

        if ($this->success) {
            $redirect->with('pipeline_success', $this->message);
            if ($this->data) {
                $redirect->with('pipeline_data', $this->data);
            }
        } else {
            $redirect->with('pipeline_error', $this->message);
            if ($this->data) {
                $redirect->with('pipeline_errors', $this->data);
            }
        }

        if (! empty($this->meta)) {
            $redirect->with('pipeline_meta', $this->meta);
        }

        if ($this->requestId) {
            $redirect->with('pipeline_request_id', $this->requestId);
        }

        return $redirect;
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'data' => $this->data,
            'status' => $this->status,
            'error_code' => $this->errorCode,
            'meta' => $this->meta,
            'request_id' => $this->requestId,
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Convert to JSON string.
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the object into something JSON serializable.
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Allow casting to a JsonResponse directly.
     */
    public function __invoke(): JsonResponse
    {
        return $this->toApiResponse();
    }

    /**
     * Magic method to convert to string.
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
