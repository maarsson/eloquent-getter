<?php

namespace Maarsson\Repository\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasModelEvents
{
    protected $availableEvents = [
        'IsCreating',
        'WasCreated',
    ];

    protected $events = [];

    protected $listeners = [];

    protected function setEventsForModel($model)
    {
        $modelName = $this->getModelName($model);

        collect($this->availableEvents)->each(
            function ($event) use ($modelName) {
                $this->events[$event] = $this->getEvent($modelName, $event);
                $this->listeners[$event] = $this->getListener($modelName, $event);
            }
        );
    }

    protected function getModelName($model)
    {
        if ($model instanceof Model) {
            return Str::remove($this->getModelsNamespace(), get_class($model));
        }

        return $model;
    }

    protected function getEvent(string $model, string $event)
    {
        return sprintf(
            '%s%s%sEvent',
            $this->getEventsNamespace(),
            $model,
            $event
        );
    }

    protected function getListener(string $model, string $event)
    {
        return sprintf(
            '%s%s%sListener',
            $this->getListenersNamespace(),
            $model,
            $event
        );
    }
}
