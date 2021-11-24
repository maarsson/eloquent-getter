<?php

namespace Maarsson\Repository\Providers;

use Illuminate\Support\ServiceProvider;
use Maarsson\Repository\Console\Commands\MakeRepositoryCommand;
use Maarsson\Repository\Providers\EventServiceProvider;
use Maarsson\Repository\Interfaces\EloquentRepositoryInterface;
use Maarsson\Repository\Repositories\BaseRepository;

class RepositoryServiceProvider extends ServiceProvider
{
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
        $this->registerProviders();
        $this->registerBindings();
        $this->registerCommands();
    }

    /**
     * Setup the configuration.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/repository.php',
            'repository'
        );
    }

    /**
     * Register package providers.
     *
     * @return void
     */
    protected function registerProviders()
    {
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Bind interfaces and repositories.
     *
     * @return void
     */
    protected function registerBindings()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);

        foreach (config('repository.models') as $model) {
            $this->app->bind(
                'App\\Interfaces\\' . $model . 'RepositoryInterface',
                'App\\Repositories\\' . $model . 'Repository',
            );
        }
    }

    /**
     * Register the package commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands(MakeRepositoryCommand::class);
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/repository.php' => $this->app->configPath('repository.php'),
            ], 'repository-config');
        }
    }
}
