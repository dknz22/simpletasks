<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Role model representing user roles in the system.
 */
class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name'];

    /**
     * Get the employees associated with this role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */    
    public function employees()
    {
        return $this->belongsToMany(Employee::class);
    }
}
