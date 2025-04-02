<?php

namespace App\Events;

use App\Models\TaskList;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class TaskListUpdated implements ShouldBroadcast
{
    use SerializesModels;

    public $taskList;

    public function __construct(TaskList $taskList)
    {
        $this->taskList = $taskList;
    }

    public function broadcastOn()
    {
        return new Channel('task-lists');
    }

    public function broadcastAs()
    {
        return 'TaskListUpdated';
    }
}