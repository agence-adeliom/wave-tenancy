<?php

namespace Wave\Actions\Workspace;

use App\Models\Workspace;
use App\Models\User;
use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Wave\Events\InvitingWorkspaceMember;
use Wave\Mail\WorkspaceInvitation;
use Wave\Rules\Role;
use Wave\Wave;


class InviteWorkspaceMember
{
    /**
     * Invite a new workspace member to the given workspace.
     */
    public function invite(User $user, Workspace $workspace, string $email, ?string $role = null): void
    {
        Gate::forUser($user)->authorize('addWorkspaceMember', $workspace);

        $this->validate($workspace, $email, $role);

        InvitingWorkspaceMember::dispatch($workspace, $email, $role);

        $invitation = $workspace->workspaceInvitations()->create([
            'email' => $email,
            'role' => $role,
        ]);

        Mail::to($email)->send(new WorkspaceInvitation($invitation));
    }

    /**
     * Validate the invite member operation.
     */
    protected function validate(Workspace $workspace, string $email, ?string $role): void
    {
        Validator::make([
            'email' => $email,
            'role' => $role,
        ], $this->rules($workspace), [
            'email.unique' => __('This user has already been invited to the workspace.'),
        ])->after(
            $this->ensureUserIsNotAlreadyOnWorkspace($workspace, $email)
        )->validateWithBag('addWorkspaceMember');
    }

    /**
     * Get the validation rules for inviting a workspace member.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    protected function rules(Workspace $workspace): array
    {
        return array_filter([
            'email' => [
                'required', 'email',
                Rule::unique(config('wave.workspace_invitation_model'))->where(function (Builder $query) use ($workspace) {
                    $query->where('workspace_id', $workspace->id);
                }),
            ],
            'role' => Wave::hasRoles()
                            ? ['required', 'string', new Role]
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
