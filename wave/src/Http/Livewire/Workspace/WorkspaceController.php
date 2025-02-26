<?php

namespace Wave\Http\Livewire\Workspace;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Wave\Wave;

class WorkspaceController extends Controller
{
    /**
     * Show the workspace management screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        if (Gate::denies('view', $request->user()->currentWorkspace)) {
            abort(403);
        }

        return view('theme::workspaces.show', [
            'user' => $request->user(),
            'workspace' => $request->user()->currentWorkspace,
        ]);
    }

    /**
     * Show the workspace creation screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        Gate::authorize('create', Wave::newWorkspaceModel());

        return view('theme::pages.workspaces.create', [
            'user' => $request->user(),
        ]);
    }
}
