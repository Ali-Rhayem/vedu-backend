<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignmentDocumentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseInstructorController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\MessageController;
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

Route::apiResource('courses', CourseController::class);

Route::apiResource('assignments', AssignmentController::class);

Route::apiResource('assignment-documents', AssignmentDocumentController::class);

Route::apiResource('course-instructor', CourseInstructorController::class);

Route::apiResource('course-student', CourseStudentController::class);

Route::apiResource('submission', SubmissionController::class);

Route::get('course-student/course/{course_id}/students', [CourseStudentController::class, 'getCourseStudents']);

Route::get('course-instructor/course/{course_id}/instructors', [CourseInstructorController::class, 'getCourseInstructors']);

Route::apiResource('chats', ChatController::class);
Route::get('chats/{chat}/messages', [ChatController::class, 'messages']);

Route::prefix('messages')->group(function () {
    Route::post('/', [MessageController::class, 'store']);
    Route::get('/{chat_id}', [MessageController::class, 'index']);
    Route::get('/{message}', [MessageController::class, 'show']);
    Route::put('/{message}', [MessageController::class, 'update']);
});