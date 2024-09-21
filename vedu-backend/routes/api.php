<?php

use App\Events\CodeUpdated;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignmentDocumentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChromeExtensionSummaryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseInstructorController;
use App\Http\Controllers\CourseStudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SubmissionGradeController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\VideoController;
use App\Models\ChromeExtensionSummary;
use Illuminate\Support\Facades\Broadcast;

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

Route::middleware('auth:api')->group(function () {
    Route::apiResource('courses', CourseController::class);

    Route::apiResource('assignments', AssignmentController::class);
    Route::get('assignments/course/{course_id}', [AssignmentController::class, 'getCourseAssignments']);

    Route::apiResource('assignment-documents', AssignmentDocumentController::class);

    Route::apiResource('course-instructor', CourseInstructorController::class);

    Route::apiResource('topic', TopicController::class);
    Route::get('assignments/course/{course_id}/by-topic', [AssignmentController::class, 'getAssignmentsByTopic']);

    Route::apiResource('course-student', CourseStudentController::class);
    Route::apiResource('submission', SubmissionController::class);

    Route::get('course-student/course/{course_id}/students', [CourseStudentController::class, 'getCourseStudents']);

    Route::get('course-instructor/course/{course_id}/instructors', [CourseInstructorController::class, 'getCourseInstructors']);

    Route::apiResource('chats', ChatController::class);

    Route::prefix('messages')->group(function () {
        Route::post('/', [MessageController::class, 'store']);
        Route::get('/{chat_id}', [MessageController::class, 'index']);
        Route::get('/{message}', [MessageController::class, 'show']);
        Route::put('/{message}', [MessageController::class, 'update']);
        Route::delete('/{message}', [MessageController::class, 'destroy']);
    });

    Route::get('/chats/{chat}/summary', [ChromeExtensionSummaryController::class, 'generateSummary']);

    Route::prefix('chrome-extension-summary')->group(function () {
        Route::get('/{chat_id}', [ChromeExtensionSummary::class, 'index']);
        Route::get('/{message}', [ChromeExtensionSummary::class, 'show']);
        Route::delete('/{message}', [ChromeExtensionSummary::class, 'destroy']);
    });

    Route::apiResource('submission-grades', SubmissionGradeController::class);
    Route::get('student/{userId}/courses', [CourseStudentController::class, 'getStudentCourses']);

    Route::get('/courses/{courseId}/is-instructor/{userId}', [CourseInstructorController::class, 'isUserInstructor']);

    Route::get('/assignments/{assignmentId}/submissions', [SubmissionController::class, 'getSubmissionsByAssignment']);

    Route::get('/topic/class/{class_id}', [TopicController::class, 'getTopicsByClass']);

    Route::post('/assignments/{assignment}/submissions/{submission}/grade', [SubmissionController::class, 'gradeSubmission']);

    Route::post('/courses/join', [CourseStudentController::class, 'joinClass']);

    Route::post('/get-user-id', [AuthController::class, 'getUserIdByEmail']);

    Route::get('course/{course_id}/users', [CourseController::class, 'getUsers']);


    Route::post('/chats/check-existing', [ChatController::class, 'checkExistingChat']);

    Route::get('/submissions/{submissionId}/download', [SubmissionController::class, 'downloadFile']);
});

Route::middleware('auth:api')->get('/user/courses', [AuthController::class, 'getUserCourses']);

Route::middleware('auth:api')->group(function () {
    Route::put('user/update-personal-info', [AuthController::class, 'updatePersonalInfo']);
    Route::post('user/update-profile', [AuthController::class, 'updateProfile']);
    Route::put('user/update-address', [AuthController::class, 'updateAddress']);
});

Route::get('/users', [AuthController::class, 'getAllUsers']);
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/dashboard-stats', [DashboardController::class, 'getDashboardStats']);