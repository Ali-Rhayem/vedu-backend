<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use GuzzleHttp\Psr7\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return response()->json([
            "courses" => $courses
        ],200);
    }

    public function store(StoreCourseRequest $request)
    {
        $course = Course::create($request->validated());
        return response()->json([
            "course" => $course
        ],201);
    }

    public function show(Course $course)
    {
        return response()->json([
            "course" => $course
        ]);
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        $course->update($request->validated());
        return response()->json([
            "course" => $course
        ],200);
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return response()->json(null, 204);
    }

    public function getUsers($course_id)
    {
        $course = Course::findOrFail($course_id);

        $instructors = $course->instructors()->get();
        $students = $course->students()->get();

        $users = $instructors->merge($students);

        return response()->json([
            "course_id" => $course_id,
            "users" => $users
        ], 200);
    }
    
}
