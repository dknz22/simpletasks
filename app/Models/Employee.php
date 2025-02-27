<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Employee extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'status'];

    public function tasks() {
        return $this->belongsToMany(Task::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
