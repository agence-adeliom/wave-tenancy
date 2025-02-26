<?php

namespace Wave\Events;

use App\Models\Workspace;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class WorkspaceEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Workspace  $workspace
     * @return void
     */
    public function __construct(public Workspace $workspace)
    {
    }
}
