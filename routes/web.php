<?php


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

Route::get('/', 'TasksController@index')->middleware('auth');

// Route::get('/', function () {
//     $tasks = App\ListModel::with('tasks')->get();

//     return $tasks;
// });

Route::get('/login', 'AuthController@index')->middleware('guest')->name('login');
Route::get('/logout', 'AuthController@logout');

// AJAX Routes
Route::get('/user', 'AuthController@getUser');
Route::get('/tasks/list/{list}', 'TasksController@getSpecificTasks');

Route::post('/tasks/{task}/check', 'TasksController@markTask');
Route::post('/login', 'AuthController@login');
