<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TaskModel;
use App\ListModel;

class TasksController extends Controller
{
    public function index(ListModel $lists, TaskModel $tasks)
    {
        $lists = auth()->user()->lists;
        $tasks = auth()->user()->tasks;

        return view('index', compact('lists', 'tasks'));
    }

    public function markTask(Request $request, TaskModel $task)
    {
        if (!request()->ajax()) {
            abort(404);
        }

        $request->validate([
            'task' => 'required'
        ]);

        ($task->completed === 1) ? $task->completed = 0 : $task->completed = 1;

        $task->save();
    }

    public function getSpecificTasks($list)
    {
        if (!request()->ajax()) {
            abort(404);
        }
        $list = ListModel::find($list);

        if ($list->user_id !== auth()->user()->id) {
            return [];
        }

        $tasks = $list->tasks;

        foreach ($tasks as $task) {
            if ($task->user_id !== auth()->user()->id) {
                return [];
            }
        }

        return $tasks;
    }
}
