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

        return view('tasks.index', compact('lists', 'tasks'));
    }

    public function create()
    {
        $lists = auth()->user()->lists;

        return view('tasks.create', compact('lists'));
    }

    public function store(Request $request)
    {
        if (!request()->ajax()) {
            abort(404);
        }

        $request->validate([
            'task' => 'required',
            'list' => 'required'
        ]);

        $task = new TaskModel;
        $task->body = $request->task;
        $task->user_id = auth()->user()->id;
        $task->list_id = $request->list;

        $task->save();
    }

    public function change(Request $request, TaskModel $task)
    {
        $request->validate([
            'task' => 'required'
        ]);

        $task->body = $request->task;

        $task->save();

        return auth()->user()->tasks;
    }

    public function delete(TaskModel $task)
    {
        if (!request()->ajax()) {
            abort(404);
        }

        $task->delete();

        return auth()->user()->tasks;
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
