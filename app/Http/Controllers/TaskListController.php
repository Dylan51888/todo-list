<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;
use Inertia\Inertia;
class TaskListController extends Controller
{
    public function index()
    {
        return TaskList::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $taskList = TaskList::create([
            'name' => $request->name
        ]);

        return response()->json($taskList);
    }

    public function show(TaskList $taskList)
    {
        return Inertia::render('Show', [
            'taskList' => $taskList,
        ]);
    }

    public function update(Request $request, TaskList $taskList)
    {
        $request->validate(['name' => 'required|string']);
        $taskList->update(['name' => $request->name]);
        return $taskList;
    }

    public function destroy(TaskList $taskList)
    {
        $taskList->delete();
        return response()->json(['message' => 'Deleted']);
    }
}