<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $assignment = Assignment::all();
        return response()->json([
            "courses" => $assignment
        ],200);
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
    public function store(StoreAssignmentRequest $request)
    {
        $assignment = Assignment::create($request->validated());
        return response()->json([
            "message" => "Assignment created successfully.",
            "assignment" => $assignment
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment)
    {
        return response()->json([
            "assignment" => $assignment
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssignmentRequest $request, Assignment $assignment)
    {
        $assignment->update($request->validated());
        return response()->json([
            "message" => "Assignment updated successfully.",
            "assignment" => $assignment
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return response()->json([
            "message" => "Assignment deleted successfully."
        ], 200);
    }

    public function getCourseAssignments($course_id)
    {
        $assignments = Assignment::where('course_id', $course_id)->get();
        return response()->json([
            "assignments" => $assignments
        ], 200);
    }

    public function getAssignmentsByTopic($course_id)
    {
        $assignments = Assignment::where('course_id', $course_id)
            ->with('topic')
            ->get()
            ->groupBy('topic.name');

        return response()->json([
            "topics" => $assignments
        ], 200);
    }
}
