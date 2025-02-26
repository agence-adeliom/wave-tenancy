<?php

namespace Wave\Http\Controllers\Workspace;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Wave\Actions\Workspace\AddWorkspaceMember;

class WorkspaceInvitationController extends Controller
{
    /**
     * Accept a workspace invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $invitationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Request $request, $invitationId)
    {
        $model = config('wave.workspace_invitation_model');

        $invitation = $model::whereKey($invitationId)->firstOrFail();

        app(AddWorkspaceMember::class)->add(
            $invitation->workspace->owner,
            $invitation->workspace,
            $invitation->email,
            $invitation->role
        );

        $invitation->delete();

        return redirect('dashboard')->banner(
            __('Great! You have accepted the invitation to join the :workspace workspace.', ['workspace' => $invitation->workspace->name]),
        );
    }

    /**
     * Cancel the given workspace invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $invitationId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $invitationId)
    {
        $model = config('wave.workspace_invitation_model');

        $invitation = $model::whereKey($invitationId)->firstOrFail();

        if (! Gate::forUser($request->user())->check('removeWorkspaceMember', $invitation->workspace)) {
            throw new AuthorizationException;
        }

        $invitation->delete();

        return back(303);
    }
}
