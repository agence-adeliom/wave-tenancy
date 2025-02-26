<?php

namespace Wave\Events;

use App\Models\Workspace;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class MembershipEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $workspace
     * @param  mixed  $user
     * @return void
     */
    public function __construct(public mixed $workspace, public mixed $user)
    {
    }
}
