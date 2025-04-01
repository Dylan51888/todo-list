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
    
        $taskLists = TaskList::create(['name' => $request->name]);

        return Inertia::render('Show', [
            'taskLists' => $taskLists,
        ]);
    }
    
    public function show(TaskList $taskLists)
    {
        $taskLists->load('tasks'); 
        return Inertia::render('Show', [
            'taskLists' => $taskLists,
        ]);
    }

    public function update(Request $request, TaskList $taskLists)
    {
        $request->validate([
            'name' => 'required|string',
            'checked' => 'boolean'
        ]);
    
        $taskLists->update([
            'name' => $request->name,
            'checked' => $request->checked ?? false,
        ]);
    
        broadcast(new TaskListUpdated($taskLists));
    
        return response()->json($taskLists);
    }
    
    public function destroy(TaskList $taskLists)
    {
        $taskLists->delete();
    
        broadcast(new TaskListUpdated($taskLists));
    
        return response()->json(['message' => 'Deleted']);
    }
}