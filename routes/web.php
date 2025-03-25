<?php


use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TaskListController;
use App\Models\TaskList;
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $taskLists = TaskList::all();
    return Inertia::render('Dashboard', [
        'taskLists' => $taskLists,
    ]);
})->name('dashboard');


Route::get('/task-lists', [TaskListController::class, 'index'])->name('task-lists.index');
Route::post('/task-lists', [TaskListController::class, 'store'])->name('task-lists.store');
Route::put('/task-lists/{taskList}', [TaskListController::class, 'update'])->name('task-lists.update');
Route::delete('/task-lists/{taskList}', [TaskListController::class, 'destroy'])->name('task-lists.destroy');
Route::get('/task-lists/{taskList}', [TaskListController::class, 'show'])->name('task-lists.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::resource('games', GameController::class)->only(['index', 'store', 'show', 'update']);
    // Route::post('/games/{game}/join', [GameController::class, 'join'])->name('games.join');
});

require __DIR__.'/auth.php';
