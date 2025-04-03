<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TaskListController;
use App\Models\TaskList;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'      => Route::has('login'),
        'canRegister'   => Route::has('register'),
        'laravelVersion'=> Application::VERSION,
        'phpVersion'    => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $taskLists = TaskList::all();
    return Inertia::render('Dashboard', [
        'taskLists' => $taskLists,
    ]);
})->name('dashboard');


Route::prefix('task-lists')->group(function () {

    Route::get('/', [TaskListController::class, 'index'])->name('task-lists.index');
    Route::post('/', [TaskListController::class, 'store'])->name('task-lists.store');
    Route::get('/{taskList}', [TaskListController::class, 'show'])->name('task-lists.show');
    Route::put('/{taskList}', [TaskListController::class, 'update'])->name('task-lists.update');
    Route::delete('/{taskList}', [TaskListController::class, 'destroy'])->name('task-lists.destroy');
    
    Route::post('/task-lists/{taskList}/tasks', [TaskListController::class, 'storeTask'])->name('task-lists.tasks.store');
    Route::put('/{taskList}/tasks', [TaskListController::class, 'update'])->name('task-lists.tasks.update');
    Route::delete('/{taskList}/tasks', [TaskListController::class, 'destroyAll'])->name('task-lists.tasks.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';