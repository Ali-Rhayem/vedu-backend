<?php

namespace App\Http\Controllers;

use App\Models\SubmissionGrade;
use App\Http\Requests\StoreSubmissionGradeRequest;
use App\Http\Requests\UpdateSubmissionGradeRequest;

class SubmissionGradeController extends Controller
{
    public function index()
    {
        $grades = SubmissionGrade::all();
        return response()->json($grades);
    }


    public function store(StoreSubmissionGradeRequest $request)
    {
        $validatedData = $request->validated();

        $grade = SubmissionGrade::create($validatedData);

        return response()->json($grade, 201);
    }

    public function show(SubmissionGrade $submissionGrade)
    {
        return response()->json($submissionGrade);
    }


    public function update(UpdateSubmissionGradeRequest $request, SubmissionGrade $submissionGrade)
    {
        $validatedData = $request->validated();

        $submissionGrade->update($validatedData);

        return response()->json($submissionGrade);
    }

    public function destroy(SubmissionGrade $submissionGrade)
    {
        $submissionGrade->delete();

        return response()->json(null, 204);
    }
}
