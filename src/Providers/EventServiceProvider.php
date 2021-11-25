<?php

namespace Maarsson\Repository\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Maarsson\Repository\Traits\HasModelEvents;
use Maarsson\Repository\Traits\UsesFolderConfig;

class EventServiceProvider extends ServiceProvider
{
    use HasModelEvents, UsesFolderConfig;

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function register()
    {
        $this->registerListeners();

        parent::register();
    }

    /**
     * Register listeners.
     *
     * @return void
     */
    public function registerListeners()
    {
        collect(config('repository.models'))->each(
            fn($model) => $this->registerListener($model)
        );
    }

    /**
     * Register listener for a model.
     *
     * @return void
     */
    public function registerListener(string $model)
    {
        $this->setEventsForModel($model);

        collect($this->events)->each(
            function ($class, $event) {
                $this->listen[
                    $this->events[$event]
                ] = [
                    $this->listeners[$event],
                ];
            }
        );
    }
}
