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
    public $action; 

    public function __construct(TaskList $taskList, $action = 'updated')
    {
        $this->taskList = $taskList;
        $this->action = $action;
    }

    public function broadcastOn()
    {
        return new Channel('task-lists');
    }

    public function broadcastAs()
    {
        return 'TaskListUpdated';
    }

    public function broadcastWith()
    {
        return [
            'taskList' => $this->taskList,
            'action' => $this->action,
        ];
    }
}