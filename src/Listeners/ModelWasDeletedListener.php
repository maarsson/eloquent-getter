<?php

namespace Maarsson\Repository\Listeners;

use Maarsson\Repository\Events\ModelWasDeletedEvent;

class ModelWasDeletedListener
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
     * @param \Maarsson\Repository\Events\ModelWasDeletedEvent $event
     */
    public function handle(ModelWasDeletedEvent $event)
    {
        // Access the model using $event->model...
    }
}
