<?php

namespace Maarsson\Repository\Listeners;

use Maarsson\Repository\Events\ModelIsCreatingEvent;

class ModelIsCreatingListener
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
     * @param \Maarsson\Repository\Events\ModelIsCreatingEvent $event
     */
    public function handle(ModelIsCreatingEvent $event)
    {
        // Access the attributes using $event->attributes...
    }
}
