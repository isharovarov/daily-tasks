<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TasksController;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'jwt.verify'], function () {

    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('tasks', [TasksController::class, 'index']);
    Route::get('tasks/new', [TasksController::class, 'new']);
    Route::post('tasks/{id}/solved', [TasksController::class, 'setSolved']);
//    Route::post('tasks/assign', [TasksController::class, 'assign']);

});
