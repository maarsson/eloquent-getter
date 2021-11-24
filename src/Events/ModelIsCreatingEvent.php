<?php

namespace Maarsson\Repository\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelIsCreatingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The creating attributes data.
     *
     * @var array
     */
    public $attributes;

    /**
     * Create a new event instance.
     *
     * @param array $attributes
     *
     * @return void
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }
}
