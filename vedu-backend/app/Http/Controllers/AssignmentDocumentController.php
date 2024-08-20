<?php

namespace App\Http\Controllers;

use App\Models\AssignmentDocument;
use App\Http\Requests\StoreAssignmentDocumentRequest;
use App\Http\Requests\UpdateAssignmentDocumentRequest;

class AssignmentDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = AssignmentDocument::all();
        return response()->json($documents);
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
    public function store(StoreAssignmentDocumentRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('assignment_documents', 'public');

            $document = AssignmentDocument::create([
                'assignment_id' => $validated['assignment_id'],
                'file_url' => $filePath,
            ]);

            return response()->json(['message' => 'Document uploaded successfully', 'document' => $document], 201);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(AssignmentDocument $assignmentDocument)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssignmentDocument $assignmentDocument)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAssignmentDocumentRequest $request, AssignmentDocument $assignmentDocument)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssignmentDocument $assignmentDocument)
    {
        //
    }
}
