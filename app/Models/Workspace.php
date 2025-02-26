<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Wave\Events\WorkspaceCreated;
use Wave\Events\WorkspaceDeleted;
use Wave\Events\WorkspaceUpdated;
use Wave\Traits\Billable;

class Workspace extends \Wave\Workspace
{
    /** @use HasFactory<\Database\Factories\WorkspaceFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'personal_workspace',
        'trial_ends_at',
    ];

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => WorkspaceCreated::class,
        'updated' => WorkspaceUpdated::class,
        'deleted' => WorkspaceDeleted::class,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'personal_workspace' => 'boolean',
        ];
    }
}
