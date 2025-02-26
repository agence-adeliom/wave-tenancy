<?php

namespace Wave\Actions\Workspace;

use App\Models\Workspace;
use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Wave\Events\AddingWorkspaceMember;
use Wave\Events\WorkspaceMemberAdded;
use Wave\Wave;


class AddWorkspaceMember
{
    /**
     * Add a new workspace member to the given workspace.
     */
    public function add(User $user, Workspace $workspace, string $email, ?string $role = null): void
    {
        Gate::forUser($user)->authorize('addWorkspaceMember', $workspace);

        $this->validate($workspace, $email, $role);

        $newWorkspaceMember = Wave::findUserByEmailOrFail($email);

        AddingWorkspaceMember::dispatch($workspace, $newWorkspaceMember);

        $workspace->users()->attach(
            $newWorkspaceMember, ['role' => $role]
        );

        WorkspaceMemberAdded::dispatch($workspace, $newWorkspaceMember);
    }

    /**
     * Validate the add member operation.
     */
    protected function validate(Workspace $workspace, string $email, ?string $role): void
    {
        Validator::make([
            'email' => $email,
            'role' => $role,
        ], $this->rules(), [
            'email.exists' => __('We were unable to find a registered user with this email address.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnWorkspace($workspace, $email)
        )->validateWithBag('addWorkspaceMember');
    }

    /**
     * Get the validation rules for adding a workspace member.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    protected function rules(): array
    {
        return array_filter([
            'email' => ['required', 'email', 'exists:users'],
            'role' => Wave::hasRoles()
                            ? ['required', 'string', new \Wave\Rules\Role]
                            : null,
        ]);
    }

    /**
     * Ensure that the user is not already on the workspace.
     */
    protected function ensureUserIsNotAlreadyOnWorkspace(Workspace $workspace, string $email): Closure
    {
        return function ($validator) use ($workspace, $email) {
            $validator->errors()->addIf(
                $workspace->hasUserWithEmail($email),
                'email',
                __('This user already belongs to the workspace.')
            );
        };
    }
}
