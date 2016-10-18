<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\ApiObjects\Comment as ApiComment;
use App\Topic;
use App\Comment;
use App\Http\Controllers\Controller;

class TopicCommentsController extends Controller
{
    public function index($topicId)
    {
        $topic = Topic::findOrFail($topicId);

        $comments = $topic->comments->map(function($comment) {
            return new ApiComment($comment);
        });

        return response()->json($comments);
    }

    public function store($topicId, Request $request)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);

        $topic = Topic::findOrFail($topicId);

        $comment = new Comment;
        $comment->body = $request->get('body');
        $comment->user_id = $request->user()->id;

        $topic->comments()->save($comment);

        return response()->json(new ApiComment($comment), 201);
    }
}