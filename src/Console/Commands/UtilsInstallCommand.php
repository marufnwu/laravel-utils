<?php

namespace Marufnwu\Utils\Console\Commands;

use Illuminate\Console\Command;

/**
 * Utils Install Command.
 *
 * @author marufnwu
 * @since 1.0.0
 */
class UtilsInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'utils:install 
                            {--force : Force the operation to run when files already exist}
                            {--config : Only publish configuration}
                            {--views : Only publish views}
                            {--lang : Only publish language files}';

    /**
     * The console command description.
     */
    protected $description = 'Install Utils package resources';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Installing Laravel Utils Package...');

        if ($this->option('config')) {
            $this->publishConfig();
            return self::SUCCESS;
        }

        if ($this->option('views')) {
            $this->publishViews();
            return self::SUCCESS;
        }

        if ($this->option('lang')) {
            $this->publishLang();
            return self::SUCCESS;
        }

        // Publish all
        $this->publishConfig();
        $this->publishViews();
        $this->publishLang();

        $this->info('✅ Utils package installed successfully!');
        $this->newLine();
        $this->info('Usage examples:');
        $this->info('1. Pipeline::success($data)->toApiResponse()');
        $this->info('2. ArrayHelper::get($array, "key.nested")');
        $this->info('3. StringHelper::slug("Hello World")');
        $this->info('4. pipeline_success($data) // Global helper');
        $this->newLine();
        $this->info('Documentation: https://github.com/marufnwu/laravel-utils');

        return self::SUCCESS;
    }

    /**
     * Publish configuration.
     */
    protected function publishConfig(): void
    {
        $force = $this->option('force');
        
        $this->call('vendor:publish', [
            '--tag' => 'utils-config',
            '--force' => $force,
        ]);

        $this->info('✅ Configuration published.');
    }

    /**
     * Publish views.
     */
    protected function publishViews(): void
    {
        $this->call('vendor:publish', [
            '--tag' => 'utils-views',
            '--force' => $this->option('force'),
        ]);

        $this->info('✅ Views published.');
    }

    /**
     * Publish language files.
     */
    protected function publishLang(): void
    {
        $this->call('vendor:publish', [
            '--tag' => 'utils-lang',
            '--force' => $this->option('force'),
        ]);

        $this->info('✅ Language files published.');
    }
}