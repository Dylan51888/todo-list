<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Events\TaskListUpdated;
class TaskListController extends Controller
{
    public function index()
    {
        $taskLists = TaskList::all();

        return Inertia::render('Dashboard', [
            'taskLists' => $taskLists,
        ]);
    }


public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $taskList = TaskList::create(['name' => $request->name]);

    return redirect()->route('task-lists.show', $taskList->id);
}
    public function show(TaskList $taskList)
    {
        return Inertia::render('Show', [
            'taskList' => $taskList,
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
    
        return response()->json(['message' => 'Deleted']);
    }
}