<?php

namespace App\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;





class TaskList extends Model
{
    protected $fillable = ['name'];
 

    public function tasks()
    {
        return $this->hasMany(Task::class, 'task_list_id', 'id');
    }
}