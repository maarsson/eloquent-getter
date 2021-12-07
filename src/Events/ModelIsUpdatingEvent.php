<?php

namespace Maarsson\Repository\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelIsUpdatingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The model instance.
     *
     * @var Illuminate\Database\Eloquent\Model
     */
    public Model $model;

    /**
     * The creating attributes data.
     *
     * @var array
     */
    public array $attributes;

    /**
     * Create a new event instance.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param array $attributes
     */
    public function __construct(Model $model, array $attributes)
    {
        $this->model = $model;
        $this->attributes = $attributes;
    }
}
