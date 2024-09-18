<?php

namespace App\Http\Controllers;

use App\Models\CourseInstructor;
use App\Http\Requests\StoreCourseInstructorRequest;
use App\Http\Requests\UpdateCourseInstructorRequest;
use App\Models\CourseStudent;

class CourseInstructorController extends Controller
{
    public function index()
    {
        $courseInstructors = CourseInstructor::all();
        return response()->json($courseInstructors);
    }

    public function store(StoreCourseInstructorRequest $request)
    {
        $existingInstructor = CourseInstructor::where('course_id', $request->course_id)
            ->where('instructor_id', $request->instructor_id)
            ->first();

        $existingStudent = CourseStudent::where('course_id', $request->course_id)
            ->where('student_id', $request->instructor_id)
            ->first();

        if ($existingInstructor) {
            return response()->json(['message' => 'Already instructor in this course'], 409);
        }

        if ($existingStudent) {
            return response()->json(['message' => 'Already student in this course'], 409);
        }

        $courseInstructor = CourseInstructor::create($request->validated());

        return response()->json([
            'message' => 'Course Instructor created successfully',
            'course_instructor' => $courseInstructor
        ], 201);
    }

    public function show(CourseInstructor $courseInstructor)
    {
        return response()->json($courseInstructor);
    }

    public function update(UpdateCourseInstructorRequest $request, CourseInstructor $courseInstructor)
    {
        $courseInstructor->update($request->validated());
        return response()->json([
            "course_instructor" => $courseInstructor
        ], 200);
    }


    public function destroy(CourseInstructor $courseInstructor)
    {
        $courseInstructor->delete();
        return response()->json(['message' => 'Course Instructor deleted successfully']);
    }

    public function getCourseInstructors($course_id)
    {
        $courseInstructors = CourseInstructor::where('course_id', $course_id)
            ->with('instructor')
            ->get();

        return response()->json($courseInstructors);
    }

    public function getInstructorCourses($userId)
    {
        $instructorCourses = CourseInstructor::with('course')
            ->where('instructor_id', $userId)
            ->get()
            ->pluck('course');

        return response()->json($instructorCourses);
    }

    public function isUserInstructor($userId, $courseId)
    {
        $isInstructor = CourseInstructor::where('instructor_id', $userId)
            ->where('course_id', $courseId)
            ->exists();

        return response()->json(['is_instructor' => $isInstructor]);
    }
}
