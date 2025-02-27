<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

/**
 * Employee model representing a company employee.
 */
class Employee extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name', 'email', 'status'];

    /**
     * Get the tasks assigned to the employee.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tasks() {
        return $this->belongsToMany(Task::class);
    }

    /**
     * Get the roles assigned to the employee.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */    
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
