<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $topics = Topic::all();
        return response()->json([
            "topics" => $topics
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
    public function store(StoreTopicRequest $request)
    {
        //
        $topic = Topic::create($request->validated());
        return response()->json([
            "message" => "topic created successfully.",
            "topic" => $topic
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Topic $topic)
    {
        //
        return response()->json([
            "topic" => $topic
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTopicRequest $request, Topic $topic)
    {
        //
        $topic->update($request->validated());
        return response()->json([
            "message" => "topic updated successfully.",
            "topic" => $topic
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic)
    {
        //
        $topic->delete();
        return response()->json([
            "message" => "topic deleted successfully."
        ], 200);
    }

    public function getTopicsByClass($class_id)
    {
        $topics = Topic::where('course_id', $class_id)->get();

        return response()->json([
            "topics" => $topics
        ], 200);
    }
}
