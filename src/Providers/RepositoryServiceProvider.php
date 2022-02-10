<?php

namespace Maarsson\Repository\Providers;

use Illuminate\Support\ServiceProvider;
use Maarsson\Repository\Console\Commands\MakeFilterCommand;
use Maarsson\Repository\Console\Commands\MakeRepositoryCommand;
use Maarsson\Repository\Contracts\EloquentRepositoryContract;
use Maarsson\Repository\Repositories\EloquentRepository;
use Maarsson\Repository\Traits\UsesFolderConfig;

class RepositoryServiceProvider extends ServiceProvider
{
    use  UsesFolderConfig;

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
    protected function configure(): void
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
    protected function registerProviders(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Bind interfaces and repositories.
     *
     * @return void
     */
    protected function registerBindings(): void
    {
        $this->app->bind(EloquentRepositoryContract::class, EloquentRepository::class);

        collect(config('repository.models'))->each(
            fn ($model) => $this->registerBinding($model)
        );
    }

    /**
     * Bind interface and repository of a model.
     *
     * @param string $model
     *
     * @return void
     */
    protected function registerBinding(string $model): void
    {
        if (
            ! $this->modelExists($model)
            || ! $this->repositoryExists($model . 'Repository')
            || ! $this->contractExists($model . 'RepositoryContract')
        ) {
            return;
        }

        $this->app->bind(
            $this->getContractsNamespace() . $model . 'RepositoryContract',
            $this->getRepositoriesNamespace() . $model . 'Repository',
        );
    }

    /**
     * Register the package commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        $this->commands(MakeFilterCommand::class);
        $this->commands(MakeRepositoryCommand::class);
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
                __DIR__ . '/../../config/repository.php' => $this->app->configPath('repository.php'),
            ], 'repository-config');
        }
    }
}
