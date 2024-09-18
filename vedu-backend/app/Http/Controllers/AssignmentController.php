<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Models\Topic;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assignments = Assignment::all();
        return response()->json([
            "assignments" => $assignments
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAssignmentRequest $request)
    {
        // Create the assignment with the validated data, including max_grade
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
        // Load related documents and submissions if necessary
        $assignment->load('documents', 'submissions');

        return response()->json([
            "assignment" => $assignment
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssignmentRequest $request, Assignment $assignment)
    {
        $assignment->update($request->validated());

        if ($request->has('topic_id') && $request->topic_id !== $assignment->topic_id) {
            $assignment->topic_id = $request->input('topic_id');
            $assignment->save();
        }

        if ($request->hasFile('documents')) {
            $assignment->documents()->delete();

            foreach ($request->file('documents') as $document) {
                $assignment->documents()->create([
                    'file_path' => $document->store('assignment_documents', 'public'),
                    'file_name' => $document->getClientOriginalName(),
                ]);
            }
        }

        return response()->json([
            "message" => "Assignment updated successfully.",
            "assignment" => $assignment->load('documents')
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
        $topics = Topic::where('course_id', $course_id)->get();

        $groupedAssignments = [];

        foreach ($topics as $topic) {
            $groupedAssignments[$topic->name] = [
                'id' => $topic->id,
                'assignments' => [],
            ];

            Assignment::where('topic_id', $topic->id)
                ->with(['submissions.student', 'documents'])
                ->chunk(100, function ($assignments) use (&$groupedAssignments, $topic) {
                    $groupedAssignments[$topic->name]['assignments'] = array_merge(
                        $groupedAssignments[$topic->name]['assignments'],
                        $assignments->toArray()
                    );
                });
        }

        return response()->json([
            "topics" => $groupedAssignments
        ], 200);
    }
}
