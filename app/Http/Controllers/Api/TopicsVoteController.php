<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicsVoteController extends Controller
{
    public function store($topicId, Request $request)
    {
        $data = [
            'user_id' => Auth::user()->id,
            'topic_id' => $topicId
        ];

        if (Vote::where($data)->count() > 0) {
            return response('', 204);
        }

        Vote::create($data);

        return response('', 200);
    }
}
