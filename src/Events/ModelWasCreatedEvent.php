<?php

namespace Maarsson\Repository\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelWasCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The model instance.
     *
     * @var Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * The creating attributes data.
     *
     * @var array
     */
    public $attributes;

    /**
     * Create a new event instance.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param array $attributes
     *
     * @return void
     */
    public function __construct(Model $model, array $attributes)
    {
        $this->model = $model;
        $this->attributes = $attributes;
    }
}
