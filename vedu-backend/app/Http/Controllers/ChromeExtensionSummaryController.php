<?php

namespace App\Http\Controllers;

use App\Models\ChromeExtensionSummary;
use App\Http\Requests\StoreChromeExtensionSummaryRequest;
use App\Http\Requests\UpdateChromeExtensionSummaryRequest;

class ChromeExtensionSummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $summaries = ChromeExtensionSummary::all();
        return response()->json([
            "summaries" => $summaries
        ], 200);
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
    public function store(StoreChromeExtensionSummaryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ChromeExtensionSummary $chromeExtensionSummary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChromeExtensionSummary $chromeExtensionSummary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChromeExtensionSummaryRequest $request, ChromeExtensionSummary $chromeExtensionSummary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChromeExtensionSummary $chromeExtensionSummary)
    {
        //
    }
}
