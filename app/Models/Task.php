<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Task extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['title', 'description', 'status'];

    public function employees() {
        return $this->belongsToMany(Employee::class);
    }
}
