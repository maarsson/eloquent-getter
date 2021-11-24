<?php

namespace Maarsson\Repository\Listeners;

use Maarsson\Repository\Events\ModelWasCreatedEvent;

class ModelWasCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Maarsson\Repository\Events\ModelWasCreated $event
     *
     * @return void
     */
    public function handle(ModelWasCreatedEvent $event)
    {
        // Access the model using $event->model...
        // Access the attributes using $event->attributes...
    }
}
