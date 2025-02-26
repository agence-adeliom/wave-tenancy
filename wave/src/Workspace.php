<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;
use Wave\Traits\Billable;

abstract class Workspace extends Model
{
    use Billable;

    /**
     * Get the owner of the workspace.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(config('wave.user_model'), 'user_id');
    }

    /**
     * Get all of the workspace's users including its owner.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allUsers()
    {
        return $this->users->merge([$this->owner]);
    }

    /**
     * Get all of the users that belong to the workspace.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('wave.user_model'), config('wave.workspace_membership_model'))
                        ->withPivot('role')
                        ->withTimestamps()
                        ->as('membership');
    }

    /**
     * Determine if the given user belongs to the workspace.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function hasUser($user)
    {
        return $this->users->contains($user) || $user->ownsWorkspace($this);
    }

    /**
     * Determine if the given email address belongs to a user on the workspace.
     *
     * @param  string  $email
     * @return bool
     */
    public function hasUserWithEmail(string $email)
    {
        return $this->allUsers()->contains(function ($user) use ($email) {
            return $user->email === $email;
        });
    }

    /**
     * Determine if the given user has the given permission on the workspace.
     *
     * @param  \App\Models\User  $user
     * @param  string  $permission
     * @return bool
     */
    public function userHasPermission($user, $permission)
    {
        return $user->hasWorkspacePermission($this, $permission);
    }

    /**
     * Get all of the pending user invitations for the workspace.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function workspaceInvitations()
    {
        return $this->hasMany(config('wave.workspace_invitation_model'));
    }

    /**
     * Remove the given user from the workspace.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function removeUser($user)
    {
        if ($user->current_workspace_id === $this->id) {
            $user->forceFill([
                'current_workspace_id' => null,
            ])->save();
        }

        $this->users()->detach($user);
    }

    /**
     * Purge all of the workspace's resources.
     *
     * @return void
     */
    public function purge()
    {
        $this->owner()->where('current_workspace_id', $this->id)
                ->update(['current_workspace_id' => null]);

        $this->users()->where('current_workspace_id', $this->id)
                ->update(['current_workspace_id' => null]);

        $this->users()->detach();

        $this->delete();
    }
}
