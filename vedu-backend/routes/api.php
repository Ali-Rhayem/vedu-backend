<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignmentDocumentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseInstructorController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\SubmissionController;

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


Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::prefix('courses')->controller(CourseController::class)->group(function () {
    Route::get('', 'index');
    Route::post('', 'store');
    Route::get('{course}', 'show');
    Route::put('{course}', 'update');
    Route::delete('{course}', 'destroy');
});

Route::apiResource('assignments', AssignmentController::class);

Route::apiResource('assignment-documents', AssignmentDocumentController::class);

Route::apiResource('course-instructor', CourseInstructorController::class);

Route::apiResource('course-student', CourseStudentController::class);

Route::apiResource('submission', SubmissionController::class);