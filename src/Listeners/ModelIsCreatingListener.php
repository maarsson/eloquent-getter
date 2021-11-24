<?php

namespace Maarsson\Repository\Listeners;

use Maarsson\Repository\Events\ModelIsCreatingEvent;

class ModelIsCreatingListener
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
     * @param Maarsson\Repository\Events\ModelIsCreating $event
     *
     * @return void
     */
    public function handle(ModelIsCreatingEvent $event)
    {
        // Access the attributes using $event->attributes...
    }
}
