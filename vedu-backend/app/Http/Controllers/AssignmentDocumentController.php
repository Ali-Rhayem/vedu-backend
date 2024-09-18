<?php

namespace App\Http\Controllers;

use App\Models\AssignmentDocument;
use App\Http\Requests\StoreAssignmentDocumentRequest;
use App\Http\Requests\UpdateAssignmentDocumentRequest;
use Illuminate\Support\Facades\Storage;

class AssignmentDocumentController extends Controller
{
    public function index()
    {
        $documents = AssignmentDocument::all();
        return response()->json($documents);
    }


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

    public function show(AssignmentDocument $assignmentDocument)
    {
        return response()->json($assignmentDocument);
    }


    public function update(UpdateAssignmentDocumentRequest $request, AssignmentDocument $assignmentDocument)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('assignment_documents', 'public');

            if ($assignmentDocument->file_url && Storage::disk('public')->exists($assignmentDocument->file_url)) {
                Storage::disk('public')->delete($assignmentDocument->file_url);
            }

            $assignmentDocument->file_url = $filePath;
            $assignmentDocument->save();

            return response()->json(['message' => 'Document updated successfully', 'document' => $assignmentDocument]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function destroy(AssignmentDocument $assignmentDocument)
    {
        Storage::disk('public')->delete($assignmentDocument->file_url);
        $assignmentDocument->delete();

        return response()->json(['message' => 'Document deleted successfully']);
    }
}
