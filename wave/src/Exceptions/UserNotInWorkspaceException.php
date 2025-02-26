<?php

namespace Wave\Exceptions;

use RuntimeException;

class UserNotInWorkspaceException extends RuntimeException
{
    /**
     * Name of the affected workspace.
     *
     * @var string
     */
    protected $workspace;

    /**
     * Set the affected workspace.
     *
     * @param  string   $workspace
     * @return $this
     */
    public function setWorkspace($workspace)
    {
        $this->workspace = $workspace;

        $this->message = "The user is not in the workspace {$workspace}";

        return $this;
    }

    /**
     * Get the affected workspace.
     *
     * @return string
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }
}
