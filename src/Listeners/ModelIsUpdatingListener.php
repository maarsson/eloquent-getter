<?php

namespace Maarsson\Repository\Listeners;

use Maarsson\Repository\Events\ModelIsUpdatingEvent;

class ModelIsUpdatingListener
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
     * @param \Maarsson\Repository\Events\ModelIsUpdatingEvent $event
     */
    public function handle(ModelIsUpdatingEvent $event)
    {
        // Access the model using $event->model...
        // Access the attributes using $event->attributes...
    }
}
