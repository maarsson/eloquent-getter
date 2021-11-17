<?php

namespace Maarsson\Repository;

use Illuminate\Support\ServiceProvider;
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
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();
        $this->registerBindings();
    }

    /**
     * Setup the configuration.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/repository.php',
            'repository'
        );
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
}
