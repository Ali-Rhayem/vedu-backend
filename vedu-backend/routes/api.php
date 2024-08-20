<?php

use App\Http\Controllers\AssignmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login',[AuthController::class ,'login']);
    Route::post('logout',[AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);

});

Route::group([
    'prefix' => 'courses'
], function ($router) {

    Route::get('',[CourseController::class ,'index']);
    Route::post('',[CourseController::class ,'store']);
    Route::get('{course}',[CourseController::class ,'show']);
    Route::put('{course}',[CourseController::class ,'update']);
    Route::delete('{course}',[CourseController::class ,'destroy']);
});

Route::group([
    'prefix' => 'assignments'
], function ($router) {

    Route::get('', [AssignmentController::class, 'index']);
    Route::post('', [AssignmentController::class, 'store']);
    Route::get('{assignment}', [AssignmentController::class, 'show']);
    Route::put('{assignment}', [AssignmentController::class, 'update']);
    Route::delete('{assignment}', [AssignmentController::class, 'destroy']);
});