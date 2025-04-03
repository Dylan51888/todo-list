<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Events\TaskListUpdated;
class TaskListController extends Controller

{
    public function index()
    {
        $taskList = TaskList::all();
        return Inertia::render('Dashboard', [
            'taskLists' => $taskList,
        ]);

    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        TaskList::create(['name' => $request->name]);

        return redirect()->route('dashboard');
    }

    
    public function show(TaskList $taskList)
    {
        $taskList->load('tasks'); 
        return Inertia::render('Show', [
            'taskLists' => $taskList,
        ]);
    }


    public function update(Request $request, TaskList $taskList)
    {
        $request->validate([
            'name' => 'required|string',
            'checked' => 'boolean'
        ]);
    
        $taskList->update([
            'name' => $request->name,
            'checked' => $request->checked ?? false,
        ]);
    
        broadcast(new TaskListUpdated($taskList));
    
        return response()->json($taskList);
    }
    

    public function destroy(TaskList $taskList)
    {
        $taskList->delete();

        broadcast(new TaskListUpdated($taskList));

        return redirect()->route('dashboard');
    }
}
