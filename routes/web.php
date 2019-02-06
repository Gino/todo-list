<?php

use App\ListModel;
use App\TaskModel;

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
