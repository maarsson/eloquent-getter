<?php

namespace Maarsson\Repository\Listeners;

use Maarsson\Repository\Events\ModelWasUpdatedEvent;

class ModelWasUpdatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \Maarsson\Repository\Events\ModelWasUpdatedEvent $event
     */
    public function handle(ModelWasUpdatedEvent $event)
    {
        // Access the model using $event->model...
        // Access the attributes using $event->attributes...
    }
}
