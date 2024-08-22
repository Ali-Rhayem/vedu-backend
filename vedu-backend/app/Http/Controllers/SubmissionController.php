<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Requests\UpdateSubmissionRequest;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function index()
    {
        $submissions = Submission::all();
        return response()->json($submissions);
    }


    public function store(StoreSubmissionRequest $request)
    {
        $validated = $request->validated();
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('submissions', 'public');
            $validated['file_url'] = $filePath;
        }
    
        $validated['submitted_at'] = now(); 
    
        $submission = Submission::create($validated);
    
        return response()->json([
            'message' => 'Submission created successfully',
            'submission' => $submission,
        ], 201);
    }


    public function show(Submission $submission)
    {
        return response()->json($submission);
    }



    public function update(UpdateSubmissionRequest $request, Submission $submission)
    {
        $validated = $request->validated();
    
        if ($request->hasFile('file')) {
            if ($submission->file_url && Storage::disk('public')->exists($submission->file_url)) {
                Storage::disk('public')->delete($submission->file_url);
            }
    
            $file = $request->file('file');
            $filePath = $file->store('submissions', 'public');
            $validated['file_url'] = $filePath;
        }
    
        $submission->update($validated);
    
        return response()->json([
            'message' => 'Submission updated successfully',
            'submission' => $submission,
        ]);
    }
    

    public function destroy($id)
    {
        $submission = Submission::find($id);
    
        if (!$submission) {
            return response()->json(['message' => 'No submission found'], 404);
        }
    
        if ($submission->file_url && Storage::disk('public')->exists($submission->file_url)) {
            Storage::disk('public')->delete($submission->file_url);
        }
    
        $submission->delete();
    
        return response()->json(['message' => 'Submission deleted successfully']);
    }
    
}
