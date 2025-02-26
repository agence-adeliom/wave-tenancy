<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;


class WorkspaceInvitation extends \Wave\WorkspaceInvitation
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'role',
    ];

    /**
     * Get the workspace that the invitation belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(config('wave.workspace_model'));
    }
}
