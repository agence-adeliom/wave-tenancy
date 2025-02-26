<?php

namespace App\Policies;

use App\Models\Workspace;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkspacePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Workspace $workspace): bool
    {
        return $user->belongsToWorkspace($workspace);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Workspace $workspace): bool
    {
        return $user->ownsWorkspace($workspace);
    }

    /**
     * Determine whether the user can add workspace members.
     */
    public function addWorkspaceMember(User $user, Workspace $workspace): bool
    {
        return $user->ownsWorkspace($workspace);
    }

    /**
     * Determine whether the user can update workspace member permissions.
     */
    public function updateWorkspaceMember(User $user, Workspace $workspace): bool
    {
        return $user->ownsWorkspace($workspace);
    }

    /**
     * Determine whether the user can remove workspace members.
     */
    public function removeWorkspaceMember(User $user, Workspace $workspace): bool
    {
        return $user->ownsWorkspace($workspace);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Workspace $workspace): bool
    {
        return $user->ownsWorkspace($workspace);
    }
}
