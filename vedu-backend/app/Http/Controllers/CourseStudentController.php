<?php

namespace App\Http\Controllers;

use App\Models\CourseStudent;
use App\Http\Requests\StoreCourseStudentRequest;
use App\Http\Requests\UpdateCourseStudentRequest;
use App\Models\Course;
use App\Models\CourseInstructor;
use Illuminate\Http\Request;

class CourseStudentController extends Controller
{
    public function index()
    {
        $courseStudents = CourseStudent::all();
        return response()->json($courseStudents);
    }

    public function store(StoreCourseStudentRequest $request)
    {
        $existingStudent = CourseStudent::where('course_id', $request->course_id)
            ->where('student_id', $request->student_id)
            ->first();

        $existingInstructor = CourseInstructor::where('course_id', $request->course_id)
            ->where('instructor_id', $request->student_id)
            ->first();

        if ($existingStudent) {
            return response()->json(['message' => 'Already student in this course'], 409);
        }

        if($existingInstructor) {
            return response()->json(['message' => 'Already Instructor in this course'], 409);
        }

        $courseStudent = CourseStudent::create($request->validated());

        return response()->json([
            'message' => 'Student created successfully',
            'course_instructor' => $courseStudent
        ], 201);
    }

    public function show(CourseStudent $courseStudent)
    {
        return response()->json($courseStudent);
    }


    public function update(UpdateCourseStudentRequest $request, CourseStudent $courseStudent)
    {
        $courseStudent->update($request->validated());
        return response()->json([
            'message' => 'Student updated successfully',
            "course_instructor" => $courseStudent
        ], 200);
    }

    public function destroy(CourseStudent $courseStudent)
    {
        $courseStudent->delete();
        return response()->json(['message' => 'student deleted successfully']);
    }


    public function getCourseStudents($course_id)
    {
        $courseStudents = CourseStudent::where('course_id', $course_id)
            ->with('student')
            ->get();

        return response()->json($courseStudents);
    }

    public function getStudentCourses($userId)
    {
        $studentCourses = CourseStudent::with('course')
            ->where('student_id', $userId)
            ->get()
            ->pluck('course');
    
        return response()->json($studentCourses);
    }
    


    public function joinClass(Request $request)
    {
        $validatedData = $request->validate([
            'class_code' => 'required|string|exists:courses,class_code',
            'student_id' => 'required|exists:users,id',
        ]);

        $course = Course::where('class_code', $validatedData['class_code'])->first();

        $existingStudent = CourseStudent::where('course_id', $course->id)
            ->where('student_id', $validatedData['student_id'])
            ->first();

        $existingInstructor = CourseInstructor::where('course_id', $course->id)
            ->where('instructor_id', $validatedData['student_id'])
            ->first();

        if ($existingStudent) {
            return response()->json(['message' => 'Already student in this class'], 409);
        }

        if ($existingInstructor) {
            return response()->json(['message' => 'Already instructor in this class'], 409);
        }

        $courseStudent = CourseStudent::create([
            'course_id' => $course->id,
            'student_id' => $validatedData['student_id'],
        ]);

        return response()->json(['message' => 'Successfully joined the class', 'course' => $courseStudent], 201);
    }
}
