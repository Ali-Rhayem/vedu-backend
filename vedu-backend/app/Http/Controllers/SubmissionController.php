<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Assignment;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSubmissionRequest;
use App\Http\Requests\UpdateSubmissionRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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

        $submission = Submission::with('student')->find($submission->id);

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

    public function getSubmissionsByAssignment($assignmentId)
    {
        $submissions = Submission::where('assignment_id', $assignmentId)
            ->with('student')
            ->get();
        return response()->json($submissions);
    }

    public function gradeSubmission(Request $request, Assignment $assignment, Submission $submission)
    {
        if (is_null($assignment->grade) || !is_numeric($assignment->grade)) {
            return response()->json([
                'message' => 'Invalid grade for the assignment.'
            ], 400);
        }

        $validatedData = $request->validate([
            'grade' => ['required', 'numeric', 'min:0', 'max:' . $assignment->grade],
        ]);

        $submission->grade = $validatedData['grade'];
        $submission->save();

        return response()->json([
            'message' => 'Grade saved successfully.',
            'submission' => $submission
        ], 200);
    }

    public function downloadFile($submissionId)
    {
        try {
            $submission = Submission::findOrFail($submissionId);

            if (!$submission->file_url || !Storage::disk('public')->exists($submission->file_url)) {
                return response()->json(['message' => 'File not found'], 404);
            }

            $fileContent = Storage::disk('public')->get($submission->file_url);
            $fileName = basename($submission->file_url);

            return response($fileContent, 200)
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error downloading file',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
