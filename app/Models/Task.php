<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

/**
 * Task model representing a task in the system.
 */
class Task extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['title', 'description', 'status'];

    /**
     * Get the employees assigned to this task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function employees() {
        return $this->belongsToMany(Employee::class);
    }
}
