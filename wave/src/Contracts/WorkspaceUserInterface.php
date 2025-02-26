<?php

namespace Wave\Contracts;

interface WorkspaceUserInterface
{
    /**
     * Many-to-Many relations with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function workspaces();

    /**
     * has-one relation with the current selected workspace model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currentWorkspace();

    /**
     * One-to-Many relation with the invite model.
     * @return mixed
     */
    public function invites();

    /**
     * Returns if the user owns a workspace.
     *
     * @return bool
     */
    public function isOwner();

    /**
     * Returns if the user owns the given workspace.
     *
     * @param mixed $workspace
     * @return bool
     */
    public function isOwnerOfWorkspace($workspace);

    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param mixed $workspace
     * @param array $pivotData
     */
    public function attachWorkspace($workspace, $pivotData = []);

    /**
     * Alias to eloquent many-to-many relation's detach() method.
     *
     * @param mixed $workspace
     */
    public function detachWorkspace($workspace);

    /**
     * Attach multiple workspaces to a user.
     *
     * @param mixed $workspaces
     */
    public function attachWorkspaces($workspaces);

    /**
     * Detach multiple workspaces from a user.
     *
     * @param mixed $workspaces
     */
    public function detachWorkspaces($workspaces);

    /**
     * Switch the current workspace of the user.
     *
     * @param object|array|int $workspace
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Wave\Exceptions\UserNotInWorkspaceException
     */
    public function switchWorkspace($workspace);
}
