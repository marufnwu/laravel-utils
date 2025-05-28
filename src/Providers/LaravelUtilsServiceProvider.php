<?php

namespace Marufnwu\Utils\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Marufnwu\Utils\Console\Commands\UtilsInstallCommand;
use Marufnwu\Utils\Helpers\ArrayHelper;
use Marufnwu\Utils\Helpers\StringHelper;
use Marufnwu\Utils\Pipeline;

/**
 * Laravel Utils Service Provider.
 *
 * @author marufnwu
 *
 * @since 1.0.0
 */
class LaravelUtilsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/utils.php', 'utils');

        // Register singletons
        $this->app->singleton('marufnwu.utils.pipeline', function () {
            return new Pipeline;
        });

        $this->app->singleton('array-helper', function () {
            return new ArrayHelper;
        });

        $this->app->singleton('string-helper', function () {
            return new StringHelper;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPublishing();
        $this->registerResources();
        $this->registerResponseMacros();
        $this->registerBladeDirectives();
        $this->registerCommands();
    }

    /**
     * Register the package's publishable resources.
     */
    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/utils.php' => config_path('utils.php'),
            ], 'utils-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/utils'),
            ], 'utils-views');

            $this->publishes([
                __DIR__.'/../resources/lang' => $this->app->langPath('vendor/utils'),
            ], 'utils-lang');
        }
    }

    /**
     * Register the package resources.
     */
    protected function registerResources(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'utils');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'utils');
    }

    /**
     * Register response macros.
     */
    protected function registerResponseMacros(): void
    {
        Response::macro('pipeline', function ($data = null, ?string $message = null, int $status = 200, array $meta = []) {
            $message = $message ?? config('utils.default_success_message', 'Operation completed successfully');

            return Pipeline::success($data, $message, $status, $meta)->toApiResponse();
        });

        Response::macro('pipelineError', function (?string $message = null, int $status = 400, $data = [], ?int $errorCode = null) {
            $message = $message ?? config('utils.default_error_message', 'An error occurred');

            return Pipeline::error($message, $status, $data, $errorCode)->toApiResponse();
        });

        Response::macro('pipelineValidation', function ($errors, ?string $message = null) {
            $message = $message ?? 'Validation failed';

            return Pipeline::validationError($errors, $message)->toApiResponse();
        });

        Response::macro('pipelineNotFound', function (?string $message = null) {
            $message = $message ?? 'Resource not found';

            return Pipeline::notFound($message)->toApiResponse();
        });

        Response::macro('pipelineUnauthorized', function (?string $message = null) {
            $message = $message ?? 'Unauthorized access';

            return Pipeline::unauthorized($message)->toApiResponse();
        });
    }

    /**
     * Register Blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        Blade::directive('pipelineSuccess', function ($expression) {
            return '<?php if(isset($pipeline) && $pipeline->isSuccess()): ?>';
        });

        Blade::directive('endpipelineSuccess', function () {
            return '<?php endif; ?>';
        });

        Blade::directive('pipelineError', function ($expression) {
            return '<?php if(isset($pipeline) && $pipeline->isError()): ?>';
        });

        Blade::directive('endpipelineError', function () {
            return '<?php endif; ?>';
        });

        Blade::directive('pipelineMessage', function ($expression) {
            return "<?php echo isset(\$pipeline) ? e(\$pipeline->message) : ''; ?>";
        });

        Blade::directive('pipelineData', function ($expression) {
            return "<?php echo isset(\$pipeline) ? json_encode(\$pipeline->getData()) : '{}'; ?>";
        });
    }

    /**
     * Register console commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                UtilsInstallCommand::class,
            ]);
        }
    }
}
