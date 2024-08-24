<?php

namespace App\Http\Controllers;

use App\Models\SubmissionGrade;
use App\Http\Requests\StoreSubmissionGradeRequest;
use App\Http\Requests\UpdateSubmissionGradeRequest;

class SubmissionGradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grades = SubmissionGrade::all();
        return response()->json($grades);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubmissionGradeRequest $request)
    {
        $validatedData = $request->validated();

        $grade = SubmissionGrade::create($validatedData);

        return response()->json($grade, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SubmissionGrade $submissionGrade)
    {
        return response()->json($submissionGrade);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubmissionGrade $submissionGrade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubmissionGradeRequest $request, SubmissionGrade $submissionGrade)
    {
        $validatedData = $request->validated();

        $submissionGrade->update($validatedData);

        return response()->json($submissionGrade);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubmissionGrade $submissionGrade)
    {
        $submissionGrade->delete();

        return response()->json(null, 204);
    }
}
