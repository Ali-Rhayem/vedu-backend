<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Http\Requests\StoreTopicRequest;
use App\Http\Requests\UpdateTopicRequest;

class TopicController extends Controller
{
    public function index()
    {
        $topics = Topic::all();
        return response()->json([
            "topics" => $topics
        ], 200);
    }

    public function store(StoreTopicRequest $request)
    {
        $topic = Topic::create($request->validated());
        return response()->json([
            "message" => "topic created successfully.",
            "topic" => $topic
        ], 201);
    }

    public function show(Topic $topic)
    {
        return response()->json([
            "topic" => $topic
        ], 200);
    }


    public function update(UpdateTopicRequest $request, Topic $topic)
    {
        $topic->update($request->validated());
        return response()->json([
            "message" => "topic updated successfully.",
            "topic" => $topic
        ], 200);
    }

    public function destroy(Topic $topic)
    {
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
