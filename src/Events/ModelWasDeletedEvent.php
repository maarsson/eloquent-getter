<?php

namespace Maarsson\Repository\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelWasDeletedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The model instance.
     *
     * @var Illuminate\Database\Eloquent\Model
     */
    public Model $model;

    /**
     * Create a new event instance.
     *
     * @param Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}
