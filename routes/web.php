<?php

use App\ListModel;
use App\TaskModel;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $lists = ListModel::all();
    $tasks = TaskModel::all();

    return view('index', compact('lists', 'tasks'));
});

Route::post('/tasks/{task}/check', function (Request $request, TaskModel $task) {
    $request->validate([
        'task' => 'required'
    ]);

    ($task->completed === 1) ? $task->completed = 0 : $task->completed = 1;

    $task->save();
});
