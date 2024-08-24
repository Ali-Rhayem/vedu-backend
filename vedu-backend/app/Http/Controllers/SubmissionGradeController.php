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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubmissionGradeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SubmissionGrade $submissionGrade)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubmissionGrade $submissionGrade)
    {
        //
    }
}
