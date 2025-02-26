<?php

namespace Wave\Actions\Workspace;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Wave\Events\WorkspaceMemberUpdated;
use Wave\Rules\Role;
use Wave\Wave;

class UpdateWorkspaceMemberRole
{
    /**
     * Update the role for the given workspace member.
     *
     * @param  mixed  $user
     * @param  mixed  $workspace
     * @param  int  $workspaceMemberId
     * @param  string  $role
     * @return void
     */
    public function update($user, $workspace, $workspaceMemberId, string $role)
    {
        Gate::forUser($user)->authorize('updateWorkspaceMember', $workspace);

        Validator::make([
            'role' => $role,
        ], [
            'role' => ['required', 'string', new Role],
        ])->validate();

        $workspace->users()->updateExistingPivot($workspaceMemberId, [
            'role' => $role,
        ]);

        WorkspaceMemberUpdated::dispatch($workspace->fresh(), Wave::findUserByIdOrFail($workspaceMemberId));
    }
}
