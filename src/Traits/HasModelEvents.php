<?php

namespace Maarsson\Repository\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasModelEvents
{
    /**
     * List of available repository event types.
     *
     * @var array
     */
    protected array $availableEventTypes = [
        'IsCreating',
        'IsUpdating',
        'WasCreated',
        'WasUpdated',
    ];

    /**
     * Container for available repository events.
     *
     * @var array
     */
    protected array $events = [];

    /**
     * Container for available repository listeners.
     *
     * @var array
     */
    protected array $listeners = [];

    /**
     * Stores the repository events and listeners to the container properties.
     *
     * @param Model|string $model
     */
    protected function setEventsForModel(Model|string $model): void
    {
        $modelName = $this->getModelName($model);

        collect($this->availableEventTypes)->each(
            function ($event) use ($modelName) {
                $this->events[$event] = $this->getEvent($modelName, $event);
                $this->listeners[$event] = $this->getListener($modelName, $event);
            }
        );
    }

    /**
     * Gets the model class name.
     *
     * @param Model|string $model
     *
     * @return string
     */
    protected function getModelName(Model|string $model): string
    {
        if ($model instanceof Model) {
            return Str::remove($this->getModelsNamespace(), get_class($model));
        }

        return $model;
    }

    protected function getEvent(string $model, string $event): string
    {
        return sprintf(
            '%s%s%sEvent',
            $this->getEventsNamespace(),
            $model,
            $event
        );
    }

    protected function getListener(string $model, string $event): string
    {
        return sprintf(
            '%s%s%sListener',
            $this->getListenersNamespace(),
            $model,
            $event
        );
    }
}
