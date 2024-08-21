<?php

namespace App\Http\Controllers;

use App\Models\CourseInstructor;
use App\Http\Requests\StoreCourseInstructorRequest;
use App\Http\Requests\UpdateCourseInstructorRequest;

class CourseInstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courseInstructors = CourseInstructor::all();
        return response()->json($courseInstructors);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseInstructorRequest $request)
    {
        $existingInstructor = CourseInstructor::where('course_id', $request->course_id)
            ->where('instructor_id', $request->instructor_id)
            ->first();
    
        if ($existingInstructor) {
            return response()->json(['message' => 'Instructor is already assigned to this course'], 409);
        }
    
        $courseInstructor = CourseInstructor::create($request->validated());
    
        return response()->json([
            'message' => 'Course Instructor created successfully',
            'course_instructor' => $courseInstructor
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseInstructor $courseInstructor)
    {
        return response()->json($courseInstructor);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseInstructor $courseInstructor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseInstructorRequest $request, CourseInstructor $courseInstructor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseInstructor $courseInstructor)
    {
        //
    }
}
