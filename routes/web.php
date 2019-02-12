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
Route::get('/profile', 'ProfileController@index')->middleware('auth');
Route::get('/task/create', 'TasksController@create')->middleware('auth');
Route::get('/list/create', 'ListsController@create')->middleware('auth');

Route::get('/login', 'AuthController@index')->middleware('guest')->name('login');

// AJAX Routes
Route::get('/user', 'AuthController@getUser');
Route::get('/users', 'AuthController@getUsers');
Route::get('/tasks/list/{list}', 'TasksController@getSpecificTasks')->middleware('auth');
Route::post('/tasks/create', 'TasksController@store')->middleware('auth');
Route::post('/lists/create', 'ListsController@store')->middleware('auth');
Route::post('/tasks/change/{task}', 'TasksController@change')->middleware('auth');
Route::post('/lists/change/{list}', 'ListsController@change')->middleware('auth');
Route::get('/tasks/delete/{task}', 'TasksController@delete')->middleware('auth');
Route::get('/lists/delete/{list}', 'ListsController@delete')->middleware('auth');
Route::post('/tasks/{task}/check', 'TasksController@markTask')->middleware('auth');
Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
Route::get('/logout', 'AuthController@logout');

// Todo: create admin area where you can see tasks of other people too when you're an administrator
