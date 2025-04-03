<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name'];
    public function tasks()
    {
        
        return $this->hasMany(Task::class, 'task_list_id', 'id');
    }
}

