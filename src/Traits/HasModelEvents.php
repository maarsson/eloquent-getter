<?php

namespace Maarsson\Repository\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasModelEvents
{
    protected $availableEvents = [
        'IsCreating',
        'WasCreated',
    ];

    protected $eventClassName = 'App\\Events\\%s%sEvent';

    protected $listenerClassName = 'App\\Listeners\\%s%sListener';

    protected $events = [];

    protected $listeners = [];

    protected function setEventsForModel($model)
    {
        $modelName = $this->getModelName($model);

        foreach ($this->availableEvents as $event) {
            $this->events[$event] = $this->getEvent($modelName, $event);
            $this->listeners[$event] = $this->getListener($modelName, $event);
        }
    }

    protected function getModelName($model)
    {
        if ($model instanceof Model) {
            return str_replace('App\\Models\\', '', get_class($model));
        }

        return $model;
    }

    protected function getEvent(string $model, string $event)
    {
        return sprintf($this->eventClassName, $model, $event);
    }

    protected function getListener(string $model, string $event)
    {
        return sprintf($this->listenerClassName, $model, $event);
    }
}
