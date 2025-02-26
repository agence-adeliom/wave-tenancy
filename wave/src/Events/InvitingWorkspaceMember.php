<?php

namespace Wave\Events;

use Illuminate\Foundation\Events\Dispatchable;

class InvitingWorkspaceMember
{
    use Dispatchable;

    /**
     * The workspace instance.
     *
     * @var mixed
     */
    public $workspace;

    /**
     * The email address of the invitee.
     *
     * @var mixed
     */
    public $email;

    /**
     * The role of the invitee.
     *
     * @var mixed
     */
    public $role;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $workspace
     * @param  mixed  $email
     * @param  mixed  $role
     * @return void
     */
    public function __construct($workspace, $email, $role)
    {
        $this->workspace = $workspace;
        $this->email = $email;
        $this->role = $role;
    }
}
