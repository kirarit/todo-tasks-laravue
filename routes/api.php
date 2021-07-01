<?php

use Illuminate\Http\Request;
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

Route::get('/todos', 'TaskController@todos')->name('loadTodos');
Route::post('/todos', 'TaskController@store')->name('storeTodos');
Route::put('/todos/{todoItem}', 'TaskController@update')->name('updateTodo');
Route::delete('/todos/{todoItem}', 'TaskController@destroy')->name('deleteTodo');
