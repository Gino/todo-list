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

Route::get('/user', 'AuthController@getUser');
Route::get('/users', 'AuthController@getUsers');
Route::get('/login', 'AuthController@index')->middleware('guest')->name('login');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'TasksController@index');
    Route::get('/profile', 'ProfileController@index');
    Route::get('/task/create/{list?}', 'TasksController@create');
    Route::get('/list/create', 'ListsController@create');

    // AJAX Routes
    Route::get('/tasks/list/{list}', 'TasksController@getSpecificTasks');
    Route::post('/tasks/create', 'TasksController@store');
    Route::post('/lists/create', 'ListsController@store');
    Route::post('/tasks/change/{task}', 'TasksController@change');
    Route::post('/lists/change/{list}', 'ListsController@change');
    Route::get('/tasks/delete/{task}', 'TasksController@delete');
    Route::get('/lists/delete/{list}', 'ListsController@delete');
    Route::post('/tasks/{task}/check', 'TasksController@markTask');
    Route::get('/logout', 'AuthController@logout');
});

Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
