<?php

namespace App\Events;

use App\Models\TaskList;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel; 
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
class TaskListUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TaskList $taskList;

    public function __construct(TaskList $taskList)
    {
        $this->taskList = $taskList;
    }

    public function broadcastOn()
    {
        return new PresenceChannel('task-lists'); 

    }

    public function broadcastAs()
    {
        return 'TaskListUpdated';
    }
}