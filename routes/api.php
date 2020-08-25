<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Auth Operations - Json Web Token
 */
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', 'AuthController@register')->name('register');
    Route::post('login', 'AuthController@login')->name('login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
    Route::put('me', 'AuthController@update_me');
});

Route::group(['middleware' => ['auth:api']], function () {

    // Users
    Route::post('users', 'UserController@store')->middleware('isAdmin');
    Route::get('users', 'UserController@index');
    Route::get('users/{user}', 'UserController@show');
    Route::match(['put', 'patch'], 'users/{user}', 'UserController@update')->middleware('isAdmin');
    Route::delete('users/{user}', 'UserController@destroy')->middleware('isAdmin');

    // Projects
    Route::post('projects', 'ProjectController@store')->middleware('isAdmin');
    Route::get('projects', 'ProjectController@index');
    Route::get('projects/{project}', 'ProjectController@show');
    Route::match(['put', 'patch'], 'projects/{project}', 'ProjectController@update')->middleware('isAdmin');
    Route::delete('projects/{project}', 'ProjectController@destroy')->middleware('isAdmin');
    Route::get('projects/{project_id}/tasks', 'ProjectController@get_project_tasks');

    // Tasks
    Route::apiResource('tasks', 'TaskController');
    Route::post('tasks', 'TaskController@store')->middleware('isAdmin');
    Route::get('tasks', 'TaskController@index');
    Route::get('tasks/{task}', 'TaskController@show');
    Route::match(['put', 'patch'], 'tasks/{task}', 'TaskController@update')->middleware('isAdmin');
    Route::delete('tasks/{task}', 'TaskController@destroy')->middleware('isAdmin');
    Route::put('tasks/{task_id}/assign_task', 'TaskController@assign_task_user')->middleware('isAdmin');
    Route::put('tasks/{task_id}/complete', 'TaskController@toggle_complete'); //IsAdmin or isAssigned
    Route::get('my_tasks', 'TaskController@mytasks');
});
