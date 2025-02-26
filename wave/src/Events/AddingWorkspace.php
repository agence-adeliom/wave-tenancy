<?php

namespace Wave\Events;

use Illuminate\Foundation\Events\Dispatchable;

class AddingWorkspace
{
    use Dispatchable;

    /**
     * The workspace owner.
     *
     * @var mixed
     */
    public $owner;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $owner
     * @return void
     */
    public function __construct($owner)
    {
        $this->owner = $owner;
    }
}
