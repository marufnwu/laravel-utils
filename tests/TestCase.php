<?php

namespace Marufnwu\Utils\Tests;

use Marufnwu\Utils\Providers\LaravelUtilsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelUtilsServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Pipeline' => \Marufnwu\Utils\Facades\Pipeline::class,
            'ArrayHelper' => \Marufnwu\Utils\Facades\ArrayHelper::class,
            'StringHelper' => \Marufnwu\Utils\Facades\StringHelper::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }
}