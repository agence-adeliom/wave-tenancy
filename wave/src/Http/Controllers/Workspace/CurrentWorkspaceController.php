<?php

namespace Wave\Http\Controllers\Workspace;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Wave\Wave;

class CurrentWorkspaceController extends Controller
{
    /**
     * Update the authenticated user's current workspace.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $workspace = Wave::newWorkspaceModel()->findOrFail($request->workspace_id);

        if (! $request->user()->switchWorkspace($workspace)) {
            abort(403);
        }

        return redirect('dashboard', 303);
    }
}
