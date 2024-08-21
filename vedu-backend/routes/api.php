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

Route::prefix('assignments')->controller(AssignmentController::class)->group(function () {
    Route::get('', 'index');
    Route::post('', 'store');
    Route::get('{assignment}', 'show');
    Route::put('{assignment}', 'update');
    Route::delete('{assignment}', 'destroy');
});

Route::prefix('assignment-documents')->controller(AssignmentDocumentController::class)->group(function () {
    Route::get('/',  'index');
    Route::post('/',  'store');
    Route::get('{assignmentDocument}',  'show');
    Route::post('{assignmentDocument}',  'update');
    Route::delete('{assignmentDocument}',  'destroy');
});

Route::prefix('course-instructor')->controller(CourseInstructorController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('{courseInstructor}', 'show');
    Route::post('{courseInstructor}', 'update');
    Route::delete('{courseInstructor}', 'destroy');
});

Route::prefix('course-student')->controller(CourseStudentController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('{courseStudent}', 'show');
    Route::post('{courseStudent}', 'update');
    Route::delete('{courseStudent}', 'destroy');
});

Route::prefix('submission')->controller(SubmissionController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
});
