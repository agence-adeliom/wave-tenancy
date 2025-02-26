<?php

namespace Wave\Actions\Workspace;

use App\Models\Workspace;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Wave\Events\WorkspaceMemberRemoved;

class RemoveWorkspaceMember
{
    /**
     * Remove the workspace member from the given workspace.
     */
    public function remove(User $user, Workspace $workspace, User $workspaceMember): void
    {
        $this->authorize($user, $workspace, $workspaceMember);

        $this->ensureUserDoesNotOwnWorkspace($workspaceMember, $workspace);

        $workspace->removeUser($workspaceMember);

        WorkspaceMemberRemoved::dispatch($workspace, $workspaceMember);
    }

    /**
     * Authorize that the user can remove the workspace member.
     */
    protected function authorize(User $user, Workspace $workspace, User $workspaceMember): void
    {
        if (! Gate::forUser($user)->check('removeWorkspaceMember', $workspace) &&
            $user->id !== $workspaceMember->id) {
            throw new AuthorizationException;
        }
    }

    /**
     * Ensure that the currently authenticated user does not own the workspace.
     */
    protected function ensureUserDoesNotOwnWorkspace(User $workspaceMember, Workspace $workspace): void
    {
        if ($workspaceMember->id === $workspace->owner->id) {
            throw ValidationException::withMessages([
                'workspace' => [__('You may not leave a workspace that you created.')],
            ])->errorBag('removeWorkspaceMember');
        }
    }
}
