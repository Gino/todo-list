<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ListModel;

class ListsController extends Controller
{
    public function create()
    {
        return view('lists.create');
    }

    public function store(Request $request)
    {
        if (!request()->ajax()) {
            abort(404);
        }

        $request->validate([
            'list' => 'required'
        ]);

        $list = new ListModel;
        $list->user_id = auth()->user()->id;
        $list->name = $request->list;
        $list->save();
    }

    public function delete(ListModel $list)
    {
        if (!request()->ajax()) {
            abort(404);
        }

        $list->delete();

        return auth()->user()->lists;
    }

    public function change(Request $request, ListModel $list)
    {
        $request->validate([
            'list' => 'required'
        ]);

        $list->name = $request->list;

        $list->save();

        return auth()->user()->lists;
    }
}
