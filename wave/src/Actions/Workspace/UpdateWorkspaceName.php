<?php

namespace Wave\Actions\Workspace;

use App\Models\Workspace;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class UpdateWorkspaceName
{
    /**
     * Validate and update the given workspace's name.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, Workspace $workspace, array $input): void
    {
        Gate::forUser($user)->authorize('update', $workspace);

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('updateWorkspaceName');

        $workspace->forceFill([
            'name' => $input['name'],
        ])->save();
    }
}
