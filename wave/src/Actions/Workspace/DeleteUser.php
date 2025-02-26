<?php

namespace Wave\Actions\Workspace;

use App\Models\Workspace;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeleteUser
{
    /**
     * Create a new action instance.
     */
    public function __construct(protected DeleteWorkspace $deletesWorkspaces)
    {
    }

    /**
     * Delete the given user.
     */
    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            $this->deleteWorkspaces($user);
            $user->deleteProfilePhoto();
            $user->tokens->each->delete();
            $user->delete();
        });
    }

    /**
     * Delete the workspaces and workspace associations attached to the user.
     */
    protected function deleteWorkspaces(User $user): void
    {
        $user->workspaces()->detach();

        $user->ownedWorkspaces->each(function (Workspace $workspace) {
            $this->deletesWorkspaces->delete($workspace);
        });
    }
}
