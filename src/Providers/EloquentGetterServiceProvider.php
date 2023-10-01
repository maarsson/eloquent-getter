<?php

namespace Maarsson\EloquentGetter\Providers;

use Illuminate\Support\ServiceProvider;
use Maarsson\EloquentGetter\Console\Commands\MakeGetterCommand;
use Maarsson\EloquentGetter\Traits\UsesFolderConfigTrait;

class EloquentGetterServiceProvider extends ServiceProvider
{
    use UsesFolderConfigTrait;

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishing();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();
        $this->registerCommands();
    }

    /**
     * Setup the configuration.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/eloquent-getter.php',
            'eloquent-getter'
        );
    }

    /**
     * Register the package commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        $this->commands(MakeGetterCommand::class);
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/eloquent-getter.php' => $this->app->configPath('eloquent-getter.php'),
            ], 'eloquent-getter-config');
        }
    }
}
