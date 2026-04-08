<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name', 'description', 'status_id', 'created_by_id', 'assigned_to_id'])]
class Task extends Model
{
    use HasFactory;

    /**
     * Get the status that owns the task.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(TaskStatus::class, 'status_id');
    }

    /**
     * Get the creator of the task.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Get the assignee of the task.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    /**
     * Labels attached to the task.
     */
    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class);
    }
}

