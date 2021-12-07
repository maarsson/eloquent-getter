<?php

namespace Maarsson\Repository\Listeners;

use Maarsson\Repository\Events\ModelIsDeletingEvent;

class ModelIsDeletingListener
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
     * @param Maarsson\Repository\Events\ModelIsDeletingEvent $event
     */
    public function handle(ModelIsDeletingEvent $event)
    {
        // Access the model using $event->model...
    }
}
