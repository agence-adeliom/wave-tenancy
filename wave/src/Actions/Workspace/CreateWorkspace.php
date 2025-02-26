<?php

namespace Wave\Actions\Workspace;

use App\Models\Workspace;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Wave\Events\AddingWorkspace;


class CreateWorkspace
{
    /**
     * Validate and create a new workspace for the given user.
     *
     * @param  array<string, string>  $input
     */
    public function create(User $user, array $input): Workspace
    {
        Gate::forUser($user)->authorize('create', config('wave.workspace_model'));

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createWorkspace');

        AddingWorkspace::dispatch($user);

        $user->switchWorkspace($workspace = $user->ownedWorkspaces()->create([
            'name' => $input['name'],
            'personal_workspace' => false,
        ]));

        return $workspace;
    }
}
