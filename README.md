# Laravel Utils

A comprehensive Laravel package providing reusable utilities including response pipeline, array helpers, string helpers, and more.

## Features

- 🔄 **Response Pipeline**: Standardized API responses with success/error handling
- 🔧 **Array Helper**: Dot notation access, filtering, grouping, and more
- 📝 **String Helper**: Advanced string manipulation utilities
- 🎨 **Blade Directives**: Custom Blade directives for pipeline responses
- 📦 **Facades**: Easy-to-use facades for all helpers
- 🌍 **Global Helpers**: Optional global helper functions

## Installation

Install via Composer:

```bash
composer require marufnwu/laravel-utils
```

For Laravel < 11, register the service provider in `config/app.php`:

```php
'providers' => [
    // ...
    Marufnwu\Utils\LaravelUtilsServiceProvider::class,
],
```

Publish configuration and resources:

```bash
php artisan utils:install
```

## Quick Start

### Pipeline Usage

```php
use Marufnwu\Utils\Pipeline;

// Success response
return Pipeline::success($data, 'User created successfully')->toApiResponse();

// Error response
return Pipeline::error('Validation failed', 422, $errors)->toApiResponse();

// For views
return Pipeline::success($users)->toViewResponse('users.index');

// For redirects
return Pipeline::success()->toRedirectResponse('dashboard');
```

### Array Helper Usage

```php
use Marufnwu\Utils\Helpers\ArrayHelper;

$array = ['user' => ['name' => 'John', 'profile' => ['age' => 25]]];

// Dot notation access
$name = ArrayHelper::get($array, 'user.name'); // 'John'
$age = ArrayHelper::get($array, 'user.profile.age'); // 25

// Set values
ArrayHelper::set($array, 'user.email', 'john@example.com');

// Filter arrays
$users = [
    ['name' => 'John', 'age' => 25],
    ['name' => 'Jane', 'age' => 30],
];
$adults = ArrayHelper::where($users, 'age', '>=', 18);

// Group by key
$grouped = ArrayHelper::groupBy($users, 'age');

// Pluck values
$names = ArrayHelper::pluck($users, 'name'); // ['John', 'Jane']
```

### String Helper Usage

```php
use Marufnwu\Utils\Helpers\StringHelper;

// Case conversion
StringHelper::camel('hello_world'); // 'helloWorld'
StringHelper::snake('HelloWorld'); // 'hello_world'
StringHelper::kebab('Hello World'); // 'hello-world'
StringHelper::studly('hello_world'); // 'HelloWorld'

// String manipulation
StringHelper::limit('Long text here...', 10); // 'Long text...'
StringHelper::words('One two three four', 2); // 'One two...'
StringHelper::slug('Hello World!'); // 'hello-world'

// String checks
StringHelper::startsWith('Hello World', 'Hello'); // true
StringHelper::contains('Hello World', 'World'); // true
StringHelper::endsWith('Hello World', 'World'); // true

// Masking
StringHelper::mask('1234567890', '*', 3, 4); // '123****890'
```

### Using Facades

```php
use Pipeline;
use ArrayHelper;
use StringHelper;