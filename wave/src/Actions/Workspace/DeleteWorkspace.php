<?php

namespace Wave\Actions\Workspace;

use App\Models\Workspace;

class DeleteWorkspace
{
    /**
     * Delete the given workspace.
     */
    public function delete(Workspace $workspace): void
    {
        $workspace->purge();
    }
}
